import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../services/api_service.dart';
import '../login_screen.dart';

class CustomerHomeScreen extends StatefulWidget {
  const CustomerHomeScreen({super.key});

  @override
  State<CustomerHomeScreen> createState() => _CustomerHomeScreenState();
}

class _CustomerHomeScreenState extends State<CustomerHomeScreen> {
  List<dynamic> _categories = [];
  List<dynamic> _products = [];
  String? _selectedCategory;
  bool _isLoading = true;
  int _loyaltyPoints = 0;
  final List<Map<String, dynamic>> _cart = [];
  String _selectedTable = '01';

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final cats = await ApiService.getCategories();
      final prods = await ApiService.getProducts(categoryId: _selectedCategory);
      final loyalty = await ApiService.getLoyaltyBalance();

      setState(() {
        _categories = cats;
        _products = prods;
        _loyaltyPoints = loyalty['data']?['current_points'] ?? 0;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  void _addToCart(Map<String, dynamic> product) {
    setState(() {
      final existingIndex = _cart.indexWhere((item) => item['product']['id'] == product['id']);
      if (existingIndex >= 0) {
        _cart[existingIndex]['quantity'] += 1;
      } else {
        _cart.add({'product': product, 'quantity': 1});
      }
    });

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('${product['name']} ditambahkan ke keranjang'),
        duration: const Duration(seconds: 1),
        backgroundColor: AppTheme.cardDark,
      ),
    );
  }

  double get _cartTotal {
    return _cart.fold(0.0, (sum, item) {
      final price = double.tryParse(item['product']['price'].toString()) ?? 0;
      return sum + (price * item['quantity']);
    });
  }

  void _showCartSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppTheme.surfaceDark,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setSheetState) {
            return Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Keranjang Pesanan', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.warmCream)),
                      DropdownButton<String>(
                        value: _selectedTable,
                        dropdownColor: AppTheme.cardDark,
                        items: ['01', '02', '03', '04', 'VIP-1', 'OUT-1'].map((t) {
                          return DropdownMenuItem(value: t, child: Text('Meja $t'));
                        }).toList(),
                        onChanged: (val) {
                          if (val != null) {
                            setState(() => _selectedTable = val);
                            setSheetState(() => _selectedTable = val);
                          }
                        },
                      ),
                    ],
                  ),
                  const Divider(color: AppTheme.borderDark),
                  if (_cart.isEmpty)
                    const Padding(
                      padding: EdgeInsets.all(32.0),
                      child: Center(child: Text('Keranjang masih kosong', style: TextStyle(color: AppTheme.textMuted))),
                    )
                  else
                    Expanded(
                      child: ListView.builder(
                        itemCount: _cart.length,
                        itemBuilder: (context, idx) {
                          final item = _cart[idx];
                          final prod = item['product'];
                          return ListTile(
                            contentPadding: EdgeInsets.zero,
                            title: Text(prod['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                            subtitle: Text('Rp ${prod['price']}'),
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                IconButton(
                                  icon: const Icon(Icons.remove_circle_outline, color: AppTheme.warmAmber),
                                  onPressed: () {
                                    setState(() {
                                      if (item['quantity'] > 1) {
                                        item['quantity'] -= 1;
                                      } else {
                                        _cart.removeAt(idx);
                                      }
                                    });
                                    setSheetState(() {});
                                  },
                                ),
                                Text('${item['quantity']}', style: const TextStyle(fontWeight: FontWeight.bold)),
                                IconButton(
                                  icon: const Icon(Icons.add_circle_outline, color: AppTheme.warmAmber),
                                  onPressed: () {
                                    setState(() => item['quantity'] += 1);
                                    setSheetState(() {});
                                  },
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                    ),
                  const Divider(color: AppTheme.borderDark),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Total Pembayaran', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text('Rp ${_cartTotal.toStringAsFixed(0)}', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: AppTheme.warmAmber)),
                    ],
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _cart.isEmpty
                          ? null
                          : () async {
                              final orderPayload = {
                                'type': 'dine_in',
                                'table_id': _selectedTable,
                                'customer_name': 'Pelanggan Mobile',
                                'payment_method': 'cash',
                                'items': _cart.map((i) => {
                                  'product_id': i['product']['id'],
                                  'quantity': i['quantity'],
                                }).toList(),
                              };

                              final res = await ApiService.createOrder(orderPayload);
                              if (!mounted) return;
                              Navigator.pop(ctx);
                              if (res['success'] == true) {
                                setState(() => _cart.clear());
                                showDialog(
                                  context: context,
                                  builder: (_) => AlertDialog(
                                    backgroundColor: AppTheme.cardDark,
                                    title: const Text('Pesanan Berhasil! 🎉'),
                                    content: Text('Nomor Order: ${res['data']['order_number']}\nPesanan Anda telah diteruskan ke Dapur & Barista.'),
                                    actions: [
                                      TextButton(
                                        onPressed: () => Navigator.pop(context),
                                        child: const Text('OK', style: TextStyle(color: AppTheme.warmAmber)),
                                      ),
                                    ],
                                  ),
                                );
                              } else {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(
                                    content: Text(res['message'] ?? 'Gagal membuat pesanan.'),
                                    backgroundColor: AppTheme.redAccent,
                                  ),
                                );
                              }
                            },
                      child: const Text('Kirim Pesanan ke Dapur'),
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Warkop Kita', style: TextStyle(fontWeight: FontWeight.w800)),
        actions: [
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
              onRefresh: _loadData,
              color: AppTheme.warmAmber,
              child: Column(
                children: [
                  // Loyalty Points Card
                  Container(
                    margin: const EdgeInsets.all(16),
                    padding: const EdgeInsets.all(18),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF2E251D), Color(0xFF1E1813)],
                      ),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: AppTheme.borderDark),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Saldo Loyalty Poin', style: TextStyle(fontSize: 12, color: AppTheme.textMuted)),
                            const SizedBox(height: 4),
                            Text('$_loyaltyPoints Poin', style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: AppTheme.warmAmber)),
                          ],
                        ),
                        ElevatedButton.icon(
                          onPressed: () {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Tukarkan 10 Poin untuk Voucher Diskon Rp 12.000!')),
                            );
                          },
                          icon: const Icon(Icons.card_giftcard, size: 16),
                          label: const Text('Tukar Voucher', style: TextStyle(fontSize: 12)),
                          style: ElevatedButton.styleFrom(padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8)),
                        ),
                      ],
                    ),
                  ),

                  // Category Chips
                  SizedBox(
                    height: 40,
                    child: ListView(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      children: [
                        ChoiceChip(
                          label: const Text('Semua'),
                          selected: _selectedCategory == null,
                          onSelected: (selected) {
                            setState(() => _selectedCategory = null);
                            _loadData();
                          },
                        ),
                        const SizedBox(width: 8),
                        ..._categories.map((cat) {
                          return Padding(
                            padding: const EdgeInsets.only(right: 8.0),
                            child: ChoiceChip(
                              label: Text(cat['name']),
                              selected: _selectedCategory == cat['id'].toString(),
                              onSelected: (selected) {
                                setState(() => _selectedCategory = selected ? cat['id'].toString() : null);
                                _loadData();
                              },
                            ),
                          );
                        }),
                      ],
                    ),
                  ),

                  const SizedBox(height: 12),

                  // Products Grid
                  Expanded(
                    child: GridView.builder(
                      padding: const EdgeInsets.all(16),
                      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        childAspectRatio: 0.75,
                        crossAxisSpacing: 14,
                        mainAxisSpacing: 14,
                      ),
                      itemCount: _products.length,
                      itemBuilder: (context, idx) {
                        final p = _products[idx];
                        return Card(
                          child: Padding(
                            padding: const EdgeInsets.all(12.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Container(
                                  height: 90,
                                  width: double.infinity,
                                  decoration: BoxDecoration(
                                    color: AppTheme.darkEspresso,
                                    borderRadius: BorderRadius.circular(10),
                                  ),
                                  child: const Icon(Icons.coffee, size: 36, color: AppTheme.warmAmber),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  p['name'],
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                                ),
                                const Spacer(),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      'Rp ${p['price']}',
                                      style: const TextStyle(fontWeight: FontWeight.w800, color: AppTheme.warmCream, fontSize: 13),
                                    ),
                                    IconButton(
                                      icon: const Icon(Icons.add_circle, color: AppTheme.warmAmber),
                                      onPressed: () => _addToCart(p),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                ],
              ),
            ),
      bottomNavigationBar: _cart.isNotEmpty
          ? Container(
              padding: const EdgeInsets.all(16),
              color: AppTheme.surfaceDark,
              child: SafeArea(
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('${_cart.length} Menu Dipilih', style: const TextStyle(fontSize: 12, color: AppTheme.textMuted)),
                        Text('Rp ${_cartTotal.toStringAsFixed(0)}', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: AppTheme.warmAmber)),
                      ],
                    ),
                    ElevatedButton.icon(
                      onPressed: _showCartSheet,
                      icon: const Icon(Icons.shopping_bag_outlined),
                      label: const Text('Buka Keranjang'),
                    ),
                  ],
                ),
              ),
            )
          : null,
    );
  }
}
