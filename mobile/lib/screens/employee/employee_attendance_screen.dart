import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image_picker/image_picker.dart';
import '../../core/theme.dart';
import '../../services/api_service.dart';
import '../login_screen.dart';

class EmployeeAttendanceScreen extends StatefulWidget {
  const EmployeeAttendanceScreen({super.key});

  @override
  State<EmployeeAttendanceScreen> createState() => _EmployeeAttendanceScreenState();
}

class _EmployeeAttendanceScreenState extends State<EmployeeAttendanceScreen> {
  bool _isLoading = false;
  String _statusText = 'Memuat status absensi...';
  bool _hasClockedIn = false;
  bool _hasClockedOut = false;
  double? _distanceFromStore;
  double? _currentLat;
  double? _currentLng;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _initLocationAndStatus();
  }

  /// Request GPS permission, get current position, then check today's attendance status.
  Future<void> _initLocationAndStatus() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      // Check & request location permission
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          setState(() {
            _errorMessage = 'Izin lokasi ditolak. Aktifkan izin GPS untuk absensi.';
            _isLoading = false;
          });
          return;
        }
      }
      if (permission == LocationPermission.deniedForever) {
        setState(() {
          _errorMessage = 'Izin lokasi ditolak permanen. Buka Pengaturan untuk mengaktifkan GPS.';
          _isLoading = false;
        });
        return;
      }

      // Get current GPS position
      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );
      _currentLat = position.latitude;
      _currentLng = position.longitude;

      // Check today's attendance status from API
      final token = await ApiService.getToken();
      if (token != null) {
        try {
          final res = await ApiService.getAttendanceToday();
          if (res['success'] == true) {
            final data = res['data'];
            final bool hasClockedIn = data['has_clocked_in'] ?? false;
            final bool hasClockedOut = data['has_clocked_out'] ?? false;
            final geofence = data['geofence_config'];

            // Calculate distance to store
            if (geofence != null) {
              final storeLat = double.tryParse(geofence['store_latitude'].toString()) ?? -6.2088;
              final storeLng = double.tryParse(geofence['store_longitude'].toString()) ?? 106.8456;
              _distanceFromStore = Geolocator.distanceBetween(
                _currentLat!, _currentLng!, storeLat, storeLng,
              );
            }

            setState(() {
              _hasClockedIn = hasClockedIn;
              _hasClockedOut = hasClockedOut;
              if (hasClockedOut) {
                _statusText = 'Shift Selesai ✅';
              } else if (hasClockedIn) {
                final clockIn = data['attendance']?['clock_in'] ?? '';
                _statusText = 'Sudah Absen Masuk ($clockIn WIB)';
              } else {
                _statusText = 'Belum Absen Masuk';
              }
            });
          }
        } catch (_) {
          // API unavailable — fallback to offline mode
          _statusText = 'Belum Absen Masuk (Offline)';
        }
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Gagal mendapatkan lokasi GPS: $e';
      });
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  /// Take selfie photo using camera
  Future<File?> _takeSelfie() async {
    if (kIsWeb) {
      // On web, image_picker uses file picker instead of camera
      final picked = await ImagePicker().pickImage(
        source: ImageSource.gallery,
        maxWidth: 800,
        imageQuality: 80,
      );
      return picked != null ? File(picked.path) : null;
    }

    final picked = await ImagePicker().pickImage(
      source: ImageSource.camera,
      preferredCameraDevice: CameraDevice.front,
      maxWidth: 800,
      imageQuality: 80,
    );
    return picked != null ? File(picked.path) : null;
  }

  /// Clock-in: GPS + Selfie → API
  Future<void> _doClockIn() async {
    if (_currentLat == null || _currentLng == null) {
      _showErrorSnack('Lokasi GPS belum tersedia. Tunggu sebentar atau refresh.');
      return;
    }

    // Take selfie
    final photo = await _takeSelfie();
    if (photo == null) {
      _showErrorSnack('Foto selfie diperlukan untuk absen masuk.');
      return;
    }

    setState(() => _isLoading = true);

    try {
      final res = await ApiService.clockIn(
        latitude: _currentLat!,
        longitude: _currentLng!,
        photoFile: photo,
      );

      if (!mounted) return;

      if (res['success'] == true) {
        setState(() {
          _hasClockedIn = true;
          _statusText = 'Sudah Absen Masuk ✅';
        });

        final distance = res['data']?['clock_in_distance_meters'];
        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            backgroundColor: AppTheme.cardDark,
            title: const Text('Absen Masuk Berhasil! ✅'),
            content: Text(
              'Lokasi valid: ${distance ?? _distanceFromStore?.toStringAsFixed(1) ?? '-'} meter dari Warkop Kita.\nSelamat bertugas!',
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('OK', style: TextStyle(color: AppTheme.warmAmber)),
              ),
            ],
          ),
        );
      } else {
        _showErrorSnack(res['message'] ?? 'Gagal absen masuk.');
      }
    } catch (e) {
      _showErrorSnack('Gagal menghubungi server: $e');
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  /// Clock-out: GPS + Selfie → API
  Future<void> _doClockOut() async {
    if (_currentLat == null || _currentLng == null) {
      _showErrorSnack('Lokasi GPS belum tersedia.');
      return;
    }

    final photo = await _takeSelfie();
    if (photo == null) {
      _showErrorSnack('Foto selfie diperlukan untuk absen pulang.');
      return;
    }

    setState(() => _isLoading = true);

    try {
      final res = await ApiService.clockOut(
        latitude: _currentLat!,
        longitude: _currentLng!,
        photoFile: photo,
      );

      if (!mounted) return;

      if (res['success'] == true) {
        setState(() {
          _hasClockedOut = true;
          _statusText = 'Shift Selesai ✅';
        });

        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            backgroundColor: AppTheme.cardDark,
            title: const Text('Absen Pulang Berhasil! 🏠'),
            content: const Text('Terima kasih atas kerja keras Anda hari ini!'),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('OK', style: TextStyle(color: AppTheme.warmAmber)),
              ),
            ],
          ),
        );
      } else {
        _showErrorSnack(res['message'] ?? 'Gagal absen pulang.');
      }
    } catch (e) {
      _showErrorSnack('Gagal menghubungi server: $e');
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _showErrorSnack(String msg) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(msg), backgroundColor: AppTheme.redAccent),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Absensi GPS Karyawan', style: TextStyle(fontWeight: FontWeight.w800)),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _isLoading ? null : _initLocationAndStatus,
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await ApiService.clearAuth();
              if (!mounted) return;
              Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
            },
          ),
        ],
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // Error message
              if (_errorMessage != null)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  margin: const EdgeInsets.only(bottom: 16),
                  decoration: BoxDecoration(
                    color: AppTheme.redAccent.withOpacity(0.15),
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: AppTheme.redAccent.withOpacity(0.3)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.warning_amber_rounded, color: AppTheme.redAccent, size: 20),
                      const SizedBox(width: 8),
                      Expanded(child: Text(_errorMessage!, style: const TextStyle(color: AppTheme.redAccent, fontSize: 12))),
                    ],
                  ),
                ),

              // GPS Card
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: AppTheme.cardDark,
                  borderRadius: BorderRadius.circular(18),
                  border: Border.all(color: AppTheme.borderDark),
                ),
                child: Column(
                  children: [
                    Icon(
                      _distanceFromStore != null ? Icons.location_on : Icons.location_searching,
                      size: 40,
                      color: _distanceFromStore != null && _distanceFromStore! <= 50
                          ? AppTheme.greenAccent
                          : AppTheme.warmAmber,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      _distanceFromStore != null ? 'Lokasi GPS Terverifikasi' : 'Mencari Lokasi GPS...',
                      style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 4),
                    if (_distanceFromStore != null)
                      Text(
                        'Jarak ke Warkop Kita: ${_distanceFromStore!.toStringAsFixed(1)} meter',
                        style: TextStyle(
                          color: _distanceFromStore! <= 50 ? AppTheme.greenAccent : AppTheme.redAccent,
                          fontWeight: FontWeight.bold,
                          fontSize: 13,
                        ),
                      )
                    else if (_isLoading)
                      const SizedBox(
                        width: 20, height: 20,
                        child: CircularProgressIndicator(strokeWidth: 2, color: AppTheme.warmAmber),
                      ),
                    const SizedBox(height: 2),
                    const Text('Radius Izin Maksimal: 50 meter', style: TextStyle(color: AppTheme.textMuted, fontSize: 11)),
                  ],
                ),
              ),

              const SizedBox(height: 28),

              // Status Box
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: AppTheme.surfaceDark,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppTheme.borderDark),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(_hasClockedIn ? Icons.check_circle : Icons.schedule, color: _hasClockedIn ? AppTheme.greenAccent : AppTheme.warmAmber),
                    const SizedBox(width: 8),
                    Text(_statusText, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                  ],
                ),
              ),

              const Spacer(),

              // Action Buttons
              if (_isLoading)
                const CircularProgressIndicator(color: AppTheme.warmAmber)
              else if (!_hasClockedIn)
                SizedBox(
                  width: double.infinity,
                  height: 54,
                  child: ElevatedButton.icon(
                    onPressed: _doClockIn,
                    icon: const Icon(Icons.camera_alt),
                    label: const Text('Ambil Selfie & Clock-In'),
                    style: ElevatedButton.styleFrom(backgroundColor: AppTheme.greenAccent, foregroundColor: Colors.black),
                  ),
                )
              else if (!_hasClockedOut)
                SizedBox(
                  width: double.infinity,
                  height: 54,
                  child: ElevatedButton.icon(
                    onPressed: _doClockOut,
                    icon: const Icon(Icons.camera_alt),
                    label: const Text('Ambil Selfie & Clock-Out'),
                    style: ElevatedButton.styleFrom(backgroundColor: AppTheme.redAccent, foregroundColor: Colors.white),
                  ),
                )
              else
                const Padding(
                  padding: EdgeInsets.all(12.0),
                  child: Text('Absensi hari ini sudah lengkap. 🎉', style: TextStyle(color: AppTheme.textMuted, fontWeight: FontWeight.bold)),
                ),

              const SizedBox(height: 20),
            ],
          ),
        ),
      ),
    );
  }
}
