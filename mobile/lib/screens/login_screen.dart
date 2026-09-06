import 'package:flutter/material.dart';
import '../core/theme.dart';
import '../services/api_service.dart';
import 'customer/customer_home_screen.dart';
import 'employee/employee_attendance_screen.dart';
import 'owner/owner_dashboard_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _loginController = TextEditingController(text: 'dadang@warkop.com');
  final _passwordController = TextEditingController(text: 'password123');
  bool _isLoading = false;
  String? _errorMessage;

  Future<void> _handleLogin([String? email, String? pass]) async {
    final loginUser = email ?? _loginController.text.trim();
    final passUser = pass ?? _passwordController.text.trim();

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final res = await ApiService.login(loginUser, passUser);
      if (res['success'] == true) {
        final token = res['data']['token'];
        final role = res['data']['user']['role'];

        await ApiService.saveToken(token);
        await ApiService.saveUserRole(role);

        if (!mounted) return;

        if (role == 'owner' || role == 'manager') {
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const OwnerDashboardScreen()));
        } else if (role == 'cashier' || role == 'barista' || role == 'waiter') {
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const EmployeeAttendanceScreen()));
        } else {
          Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const CustomerHomeScreen()));
        }
      } else {
        setState(() {
          _errorMessage = res['message'] ?? 'Login gagal. Periksa kembali akun Anda.';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Gagal menghubungi server. Pastikan API backend berjalan.';
      });
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(28.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // Icon
                Container(
                  width: 70,
                  height: 70,
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(
                      colors: [AppTheme.warmAmber, AppTheme.goldLatte],
                    ),
                    borderRadius: BorderRadius.circular(20),
                    boxShadow: [
                      BoxShadow(
                        color: AppTheme.warmAmber.withOpacity(0.3),
                        blurRadius: 20,
                        offset: const Offset(0, 8),
                      ),
                    ],
                  ),
                  child: const Icon(Icons.coffee, size: 36, color: Color(0xFF1A120B)),
                ),
                const SizedBox(height: 20),
                const Text(
                  'WARKOP KITA',
                  style: TextStyle(fontSize: 24, fontWeight: FontWeight.w800, color: AppTheme.warmCream),
                ),
                const Text(
                  'Multi-Role Portal Mobile',
                  style: TextStyle(fontSize: 13, color: AppTheme.textMuted),
                ),
                const SizedBox(height: 32),

                if (_errorMessage != null)
                  Container(
                    padding: const EdgeInsets.all(12),
                    margin: const EdgeInsets.only(bottom: 16),
                    decoration: BoxDecoration(
                      color: AppTheme.redAccent.withOpacity(0.15),
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: AppTheme.redAccent.withOpacity(0.3)),
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.error_outline, color: AppTheme.redAccent, size: 20),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            _errorMessage!,
                            style: const TextStyle(color: Color(0xFFFCA5A5), fontSize: 12),
                          ),
                        ),
                      ],
                    ),
                  ),

                TextField(
                  controller: _loginController,
                  decoration: InputDecoration(
                    labelText: 'Email atau No. HP',
                    prefixIcon: const Icon(Icons.email_outlined),
                    filled: true,
                    fillColor: AppTheme.surfaceDark,
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: AppTheme.borderDark)),
                  ),
                ),
                const SizedBox(height: 16),

                TextField(
                  controller: _passwordController,
                  obscureText: true,
                  decoration: InputDecoration(
                    labelText: 'Password',
                    prefixIcon: const Icon(Icons.lock_outline),
                    filled: true,
                    fillColor: AppTheme.surfaceDark,
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: AppTheme.borderDark)),
                  ),
                ),
                const SizedBox(height: 24),

                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : () => _handleLogin(),
                    child: _isLoading
                        ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Color(0xFF1A120B)))
                        : const Text('Masuk ke Sistem'),
                  ),
                ),

                const SizedBox(height: 32),
                const Text('UJI COBA 1-KLIK ROLE', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: AppTheme.textMuted, letterSpacing: 1)),
                const SizedBox(height: 12),

                // Quick Demo Login Buttons
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () => _handleLogin('dadang@warkop.com', 'password123'),
                        child: const Text('👑 Owner', style: TextStyle(fontSize: 12)),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () => _handleLogin('agus@warkop.com', 'password123'),
                        child: const Text('☕ Karyawan', style: TextStyle(fontSize: 12)),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () => _handleLogin('pelanggan@warkop.com', 'password123'),
                        child: const Text('👤 Pelanggan', style: TextStyle(fontSize: 12)),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
