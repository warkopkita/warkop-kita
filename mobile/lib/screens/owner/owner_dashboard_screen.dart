import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../services/api_service.dart';
import '../login_screen.dart';

class OwnerDashboardScreen extends StatefulWidget {
  const OwnerDashboardScreen({super.key});

  @override
  State<OwnerDashboardScreen> createState() => _OwnerDashboardScreenState();
}

class _OwnerDashboardScreenState extends State<OwnerDashboardScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _summaryData;

  @override
  void initState() {
    super.initState();
    _loadSummary();
  }

  Future<void> _loadSummary() async {
    setState(() => _isLoading = true);
    try {
      final res = await ApiService.getOwnerSummary();
      if (res['success'] == true) {
        setState(() {
          _summaryData = res['data'];
          _isLoading = false;
        });
      } else {
        setState(() => _isLoading = false);
      }
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final summary = _summaryData?['summary'] ?? {};
    final topProducts = (_summaryData?['top_products'] as List?) ?? [];
    final staffAttendances = (_summaryData?['staff_attendances'] as List?) ?? [];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Owner Monitoring Hub', style: TextStyle(fontWeight: FontWeight.w800)),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadSummary,
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
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppTheme.warmAmber))
          : RefreshIndicator(
              onRefresh: _loadSummary,
              color: AppTheme.warmAmber,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  // Revenue Card
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(colors: [Color(0xFF2E251D), Color(0xFF1B1612)]),
                      borderRadius: BorderRadius.circular(18),
                      border: Border.all(color: AppTheme.borderDark),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Omset Penjualan Hari Ini', style: TextStyle(fontSize: 12, color: AppTheme.textMuted)),
                        const SizedBox(height: 6),
                        Text(
                          'Rp ${(summary['revenue_today'] ?? 0).toString()}',
                          style: const TextStyle(fontSize: 26, fontWeight: FontWeight.w800, color: AppTheme.warmAmber),
                        ),
                        const SizedBox(height: 12),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text('${summary['total_orders_today'] ?? 0} Pesanan Masuk', style: const TextStyle(fontSize: 12, color: AppTheme.warmCream)),
                            Text('Laba: Rp ${(summary['net_profit_today'] ?? 0).toString()}', style: const TextStyle(fontSize: 12, color: AppTheme.greenAccent, fontWeight: FontWeight.bold)),
                          ],
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 24),
                  const Text('Top Menu Hari Ini', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: AppTheme.warmCream)),
                  const SizedBox(height: 10),

                  ...topProducts.map((tp) {
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        leading: const CircleAvatar(
                          backgroundColor: AppTheme.surfaceDark,
                          child: Icon(Icons.coffee, color: AppTheme.warmAmber),
                        ),
                        title: Text(tp['product_name'] ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                        subtitle: Text('${tp['total_qty']} porsi'),
                        trailing: Text('Rp ${tp['total_sales']}', style: const TextStyle(fontWeight: FontWeight.bold, color: AppTheme.warmAmber)),
                      ),
                    );
                  }),

                  const SizedBox(height: 24),
                  const Text('Monitoring Kehadiran Staf', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: AppTheme.warmCream)),
                  const SizedBox(height: 10),

                  ...staffAttendances.map((sa) {
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        leading: CircleAvatar(
                          backgroundColor: AppTheme.surfaceDark,
                          child: Text(sa['staff_name']?[0] ?? 'S', style: const TextStyle(fontWeight: FontWeight.bold, color: AppTheme.warmAmber)),
                        ),
                        title: Text(sa['staff_name'] ?? '', style: const TextStyle(fontWeight: FontWeight.bold)),
                        subtitle: Text('Masuk: ${sa['clock_in'] ?? '-'} • Jarak: ${sa['distance_meters'] ?? 0}m'),
                        trailing: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            color: AppTheme.greenAccent.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: const Text('Hadir Valid', style: TextStyle(color: AppTheme.greenAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                        ),
                      ),
                    );
                  }),
                ],
              ),
            ),
    );
  }
}
