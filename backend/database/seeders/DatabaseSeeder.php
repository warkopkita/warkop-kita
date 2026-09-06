<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Expense;
use App\Models\LoyaltyLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\StoreSetting;
use App\Models\Table;
use App\Models\User;
use App\Models\Variant;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $owner = User::create([
            'name' => 'Bang Dadang (Owner)',
            'email' => 'dadang@warkop.com',
            'phone' => '081211112222',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Budi Santoso (Manager)',
            'email' => 'budi@warkop.com',
            'phone' => '081233334444',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        $cashier = User::create([
            'name' => 'Siti Aminah (Kasir)',
            'email' => 'siti@warkop.com',
            'phone' => '081255556666',
            'password' => Hash::make('password123'),
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $barista = User::create([
            'name' => 'Agus Pratama (Barista)',
            'email' => 'agus@warkop.com',
            'phone' => '081277778888',
            'password' => Hash::make('password123'),
            'role' => 'barista',
            'is_active' => true,
        ]);

        $waiter = User::create([
            'name' => 'Rian Kurniawan (Waiter)',
            'email' => 'rian@warkop.com',
            'phone' => '081299990000',
            'password' => Hash::make('password123'),
            'role' => 'waiter',
            'is_active' => true,
        ]);

        $customer = User::create([
            'name' => 'Dimas Maulana (Pelanggan Setia)',
            'email' => 'pelanggan@warkop.com',
            'phone' => '085712345678',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'loyalty_points' => 125,
            'is_active' => true,
        ]);

        // 2. Categories
        $catKopi = Category::create([
            'name' => 'Kopi Signature',
            'slug' => 'kopi-signature',
            'icon' => 'coffee',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catNonKopi = Category::create([
            'name' => 'Minuman Non-Kopi',
            'slug' => 'minuman-non-kopi',
            'icon' => 'glass-water',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catMakanan = Category::create([
            'name' => 'Makanan & Mie',
            'slug' => 'makanan-mie',
            'icon' => 'utensils',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catCamilan = Category::create([
            'name' => 'Camilan & Toast',
            'slug' => 'camilan-toast',
            'icon' => 'cookie',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $catLainnya = Category::create([
            'name' => 'Rokok & Tambahan',
            'slug' => 'rokok-tambahan',
            'icon' => 'fire',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // 3. Products & Variants
        // Product 1: Kopi Tubruk Dadang
        $p1 = Product::create([
            'category_id' => $catKopi->id,
            'name' => 'Kopi Tubruk Dadang',
            'slug' => 'kopi-tubruk-dadang',
            'description' => 'Racikan biji kopi Robusta Dampit giling kasar pilihan dengan aroma pekat dan rasa bold mantap.',
            'price' => 6000,
            'cost_price' => 2000,
            'image' => 'assets/images/menu/kopi-tubruk.jpg',
            'is_favorite' => true,
        ]);
        Variant::create([
            'product_id' => $p1->id,
            'name' => 'Suhu',
            'type' => 'select',
            'options' => [
                ['name' => 'Panas', 'price' => 0],
                ['name' => 'Es / Dingin', 'price' => 1000],
            ],
            'is_required' => true,
        ]);
        Variant::create([
            'product_id' => $p1->id,
            'name' => 'Tingkat Manis',
            'type' => 'select',
            'options' => [
                ['name' => 'Manis Normal', 'price' => 0],
                ['name' => 'Sedikit Gula (Less Sweet)', 'price' => 0],
                ['name' => 'Pahit / Tanpa Gula', 'price' => 0],
            ],
            'is_required' => true,
        ]);

        // Product 2: Kopi Susu Aren Dadang
        $p2 = Product::create([
            'category_id' => $catKopi->id,
            'name' => 'Kopi Susu Aren Dadang',
            'slug' => 'kopi-susu-aren-dadang',
            'description' => 'Espresso robusta mantap dipadu susu segar creamy dan gula aren asli Lebak wangi.',
            'price' => 12000,
            'cost_price' => 5000,
            'image' => 'assets/images/menu/kopi-susu-aren.jpg',
            'is_favorite' => true,
        ]);
        Variant::create([
            'product_id' => $p2->id,
            'name' => 'Suhu & Es',
            'type' => 'select',
            'options' => [
                ['name' => 'Es Dingin', 'price' => 0],
                ['name' => 'Panas Hangat', 'price' => 0],
            ],
            'is_required' => true,
        ]);
        Variant::create([
            'product_id' => $p2->id,
            'name' => 'Extra Topping',
            'type' => 'select',
            'options' => [
                ['name' => 'Tanpa Topping', 'price' => 0],
                ['name' => 'Extra Espresso Shot', 'price' => 4000],
                ['name' => 'Grass Jelly / Cincau', 'price' => 3000],
            ],
            'is_required' => false,
        ]);

        // Product 3: Kopi Sanger Aceh
        $p3 = Product::create([
            'category_id' => $catKopi->id,
            'name' => 'Kopi Sanger Tradisional',
            'slug' => 'kopi-sanger-tradisional',
            'description' => 'Kopi tarik khas warkop dengan proporsi kopi pekat dan susu kental manis pas (Sama-sama Ngerti).',
            'price' => 9000,
            'cost_price' => 3500,
            'image' => 'assets/images/menu/kopi-sanger.jpg',
            'is_favorite' => true,
        ]);

        // Product 4: Es Teh Manis Jumbo
        $p4 = Product::create([
            'category_id' => $catNonKopi->id,
            'name' => 'Es Teh Manis Jumbo Warkop',
            'slug' => 'es-teh-manis-jumbo',
            'description' => 'Teh racikan melati wangi dengan ukuran jumbo 22oz segar pelepas dahaga.',
            'price' => 5000,
            'cost_price' => 1500,
            'image' => 'assets/images/menu/es-teh-jumbo.jpg',
            'is_favorite' => true,
        ]);

        // Product 5: Es Jeruk Peras Murni
        $p5 = Product::create([
            'category_id' => $catNonKopi->id,
            'name' => 'Es Jeruk Peras Segar',
            'slug' => 'es-jeruk-peras-segar',
            'description' => 'Jeruk peras segar asli tanpa sirup buatan.',
            'price' => 8000,
            'cost_price' => 3000,
            'image' => 'assets/images/menu/es-jeruk.jpg',
            'is_favorite' => false,
        ]);

        // Product 6: Indomie Goreng Komplit (Internet)
        $p6 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Indomie Goreng Internet (Telur + Kornet)',
            'slug' => 'indomie-goreng-internet',
            'description' => 'Indomie goreng legendaris dengan topping telur ceplok setengah matang dan tumis kornet gurih.',
            'price' => 16000,
            'cost_price' => 7000,
            'image' => 'assets/images/menu/indomie-internet.jpg',
            'is_favorite' => true,
        ]);
        Variant::create([
            'product_id' => $p6->id,
            'name' => 'Level Pedas',
            'type' => 'select',
            'options' => [
                ['name' => 'Level 0 (Tanpa Cabai)', 'price' => 0],
                ['name' => 'Level 1 (Cabai Rawit Iris Sedang)', 'price' => 0],
                ['name' => 'Level 3 (Pedas Nampol)', 'price' => 1000],
                ['name' => 'Level 5 (Gila Pedas)', 'price' => 2000],
            ],
            'is_required' => true,
        ]);
        Variant::create([
            'product_id' => $p6->id,
            'name' => 'Tambah Keju Parut',
            'type' => 'select',
            'options' => [
                ['name' => 'Tanpa Keju', 'price' => 0],
                ['name' => 'Extra Keju Kraft Parut', 'price' => 3000],
            ],
            'is_required' => false,
        ]);

        // Product 7: Nasi Goreng Gila Dadang
        $p7 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Nasi Goreng Gila Spesial',
            'slug' => 'nasi-goreng-gila-spesial',
            'description' => 'Nasi goreng racikan bumbu khas warkop dengan tumisan sosis, bakso, suwir ayam dan telur melimpah.',
            'price' => 22000,
            'cost_price' => 9000,
            'image' => 'assets/images/menu/nasgor-gila.jpg',
            'is_favorite' => true,
        ]);

        // Product 8: Pisang Goreng Keju Coklat
        $p8 = Product::create([
            'category_id' => $catCamilan->id,
            'name' => 'Pisang Goreng Keju Coklat Crispy',
            'slug' => 'pisang-goreng-keju-coklat',
            'description' => 'Pisang kepok manis digoreng tepung renyah berlimpah keju cheddar dan susu coklat kental manis.',
            'price' => 14000,
            'cost_price' => 5000,
            'image' => 'assets/images/menu/pisang-keju.jpg',
            'is_favorite' => true,
        ]);

        // Product 9: Roti Bakar Coklat Keju Susu
        $p9 = Product::create([
            'category_id' => $catCamilan->id,
            'name' => 'Roti Bakar Coklat Keju Susu',
            'slug' => 'roti-bakar-coklat-keju-susu',
            'description' => 'Roti bakar tebal empuk dengan mentega wangi, coklat meses tebal, dan keju melimpah.',
            'price' => 15000,
            'cost_price' => 5500,
            'image' => 'assets/images/menu/roti-bakar.jpg',
            'is_favorite' => false,
        ]);

        // Product 10: Tahu Cabe Garam Krispi
        $p10 = Product::create([
            'category_id' => $catCamilan->id,
            'name' => 'Tahu Cabe Garam Crispy',
            'slug' => 'tahu-cabe-garam-crispy',
            'description' => 'Tahu sutra dipotong dadu digoreng krispi dengan tumisan bawang putih dan cabai rawit pedas asin gurih.',
            'price' => 12000,
            'cost_price' => 4000,
            'image' => 'assets/images/menu/tahu-cabe-garam.jpg',
            'is_favorite' => false,
        ]);

        // 4. Tables (Meja Warkop)
        for ($i = 1; $i <= 10; $i++) {
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            Table::create([
                'number' => $num,
                'name' => 'Meja ' . $num,
                'qr_code' => 'WD-MEJA-' . $num,
                'status' => $i === 2 ? 'occupied' : ($i === 4 ? 'occupied' : 'available'),
                'capacity' => 4,
            ]);
        }
        Table::create([
            'number' => 'VIP-1',
            'name' => 'Sofa VIP AC 1',
            'qr_code' => 'WD-VIP-01',
            'status' => 'available',
            'capacity' => 8,
        ]);
        Table::create([
            'number' => 'OUT-1',
            'name' => 'Outdoor Smoking 1',
            'qr_code' => 'WD-OUT-01',
            'status' => 'available',
            'capacity' => 6,
        ]);
        Table::create([
            'number' => 'OUT-2',
            'name' => 'Outdoor Smoking 2',
            'qr_code' => 'WD-OUT-02',
            'status' => 'available',
            'capacity' => 6,
        ]);

        // 5. Raw Materials
        RawMaterial::create([
            'name' => 'Biji Kopi Robusta Dampit',
            'sku' => 'MAT-KOP-01',
            'unit' => 'kg',
            'current_stock' => 18.5,
            'min_stock_alert' => 5.0,
            'cost_per_unit' => 95000,
            'supplier' => 'Petani Dampit Malang',
        ]);
        RawMaterial::create([
            'name' => 'Susu Kental Manis Carnation',
            'sku' => 'MAT-SKM-01',
            'unit' => 'kaleng',
            'current_stock' => 32,
            'min_stock_alert' => 10,
            'cost_per_unit' => 12500,
            'supplier' => 'Agen Sembako Maju',
        ]);
        RawMaterial::create([
            'name' => 'Gula Aren Cair Asli',
            'sku' => 'MAT-ARN-01',
            'unit' => 'liter',
            'current_stock' => 12.0,
            'min_stock_alert' => 3.0,
            'cost_per_unit' => 35000,
            'supplier' => 'Pabrik Gula Aren Lebak',
        ]);
        RawMaterial::create([
            'name' => 'Indomie Goreng Original',
            'sku' => 'MAT-MIE-01',
            'unit' => 'pcs',
            'current_stock' => 120,
            'min_stock_alert' => 40,
            'cost_per_unit' => 3200,
            'supplier' => 'Distributor Indofood',
        ]);
        RawMaterial::create([
            'name' => 'Telur Ayam Segar',
            'sku' => 'MAT-TLR-01',
            'unit' => 'kg',
            'current_stock' => 15.0,
            'min_stock_alert' => 4.0,
            'cost_per_unit' => 28000,
            'supplier' => 'Peternakan Berkah',
        ]);
        RawMaterial::create([
            'name' => 'Cup Plastik 16oz + Tutup',
            'sku' => 'MAT-CUP-16',
            'unit' => 'pcs',
            'current_stock' => 4, // LOW STOCK ALERT TEST!
            'min_stock_alert' => 50,
            'cost_per_unit' => 450,
            'supplier' => 'Plastik Jaya',
        ]);

        // 6. Shifts
        $shiftPagi = Shift::create([
            'name' => 'Shift Pagi / Siang',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'is_active' => true,
        ]);
        $shiftMalam = Shift::create([
            'name' => 'Shift Malam (Peak)',
            'start_time' => '16:00:00',
            'end_time' => '00:00:00',
            'is_active' => true,
        ]);
        $shiftKalong = Shift::create([
            'name' => 'Shift Kalong (Dini Hari)',
            'start_time' => '00:00:00',
            'end_time' => '08:00:00',
            'is_active' => true,
        ]);

        // Shift Assignments for today
        ShiftAssignment::create(['user_id' => $cashier->id, 'shift_id' => $shiftPagi->id, 'date' => today()]);
        ShiftAssignment::create(['user_id' => $barista->id, 'shift_id' => $shiftPagi->id, 'date' => today()]);
        ShiftAssignment::create(['user_id' => $waiter->id, 'shift_id' => $shiftPagi->id, 'date' => today()]);

        // 7. Attendances (Hari ini)
        Attendance::create([
            'user_id' => $cashier->id,
            'shift_id' => $shiftPagi->id,
            'date' => today(),
            'clock_in' => '07:55:12',
            'clock_in_latitude' => -6.208845,
            'clock_in_longitude' => 106.845612,
            'clock_in_distance_meters' => 6.5,
            'status' => 'present',
            'notes' => 'Tepat waktu',
        ]);
        Attendance::create([
            'user_id' => $barista->id,
            'shift_id' => $shiftPagi->id,
            'date' => today(),
            'clock_in' => '07:58:30',
            'clock_in_latitude' => -6.208860,
            'clock_in_longitude' => 106.845620,
            'clock_in_distance_meters' => 8.2,
            'status' => 'present',
            'notes' => 'Siap bar',
        ]);

        // 8. Store Settings
        StoreSetting::set('store_name', 'Warkop Dadang', 'general');
        StoreSetting::set('store_tagline', 'Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang', 'general');
        StoreSetting::set('store_phone', '0812-3456-7890', 'general');
        StoreSetting::set('store_address', 'Jl. Pemuda No. 88, Rawamangun, Jakarta Timur', 'general');
        StoreSetting::set('store_open_hours', 'Buka Setiap Hari: 08.00 - 03.00 WIB', 'general');
        StoreSetting::set('store_wifi_ssid', 'WARKOP DADANG 5G', 'general');
        StoreSetting::set('store_wifi_pass', 'kopidandangjuara', 'general');
        StoreSetting::set('store_latitude', '-6.208800', 'location');
        StoreSetting::set('store_longitude', '106.845600', 'location');
        StoreSetting::set('geofence_radius_meters', '50', 'location');
        StoreSetting::set('loyalty_points_per_10k', '1', 'loyalty');
        StoreSetting::set('loyalty_point_value_idr', '1000', 'loyalty');
        StoreSetting::set('tax_percentage', '0', 'pos');
        StoreSetting::set('qris_account_name', 'WARKOP DADANG NUSANTARA', 'pos');

        // 9. Vouchers
        Voucher::create([
            'code' => 'DADANGHEMAT',
            'name' => 'Diskon Nongkrong 10%',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_purchase' => 25000,
            'max_discount' => 10000,
            'points_required' => 0,
            'start_date' => today()->subDays(5),
            'end_date' => today()->addMonths(2),
            'usage_limit' => 100,
            'used_count' => 14,
            'is_active' => true,
        ]);
        Voucher::create([
            'code' => 'GRATISKOPI',
            'name' => 'Voucher Kopi Gratis (Tukar Poin)',
            'discount_type' => 'fixed',
            'discount_value' => 12000,
            'min_purchase' => 12000,
            'points_required' => 10,
            'start_date' => today()->subDays(5),
            'end_date' => today()->addMonths(6),
            'is_active' => true,
        ]);

        // 10. Sample Orders (untuk tampilan dashboard & POS)
        // Order 1: Completed Dine-in
        $table2 = Table::where('number', '02')->first();
        $order1 = Order::create([
            'order_number' => 'WD-' . now()->format('ymd') . '-0001',
            'user_id' => $customer->id,
            'table_id' => $table2->id,
            'customer_name' => 'Dimas Maulana',
            'customer_phone' => '085712345678',
            'type' => 'dine_in',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 34000,
            'discount_amount' => 3400,
            'tax_amount' => 0,
            'total_amount' => 30600,
            'paid_amount' => 50000,
            'change_amount' => 19400,
            'payment_method' => 'cash',
            'cashier_id' => $cashier->id,
            'served_by' => $waiter->id,
            'completed_at' => now()->subMinutes(40),
            'created_at' => now()->subMinutes(60),
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p2->id,
            'product_name' => 'Kopi Susu Aren Dadang',
            'variant_options' => ['Suhu & Es' => 'Es Dingin'],
            'unit_price' => 12000,
            'quantity' => 1,
            'subtotal' => 12000,
            'status' => 'served',
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p6->id,
            'product_name' => 'Indomie Goreng Internet (Telur + Kornet)',
            'variant_options' => ['Level Pedas' => 'Level 1 (Cabai Rawit Iris Sedang)'],
            'unit_price' => 16000,
            'quantity' => 1,
            'subtotal' => 16000,
            'status' => 'served',
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => 'Kopi Tubruk Dadang',
            'variant_options' => ['Suhu' => 'Panas', 'Tingkat Manis' => 'Manis Normal'],
            'unit_price' => 6000,
            'quantity' => 1,
            'subtotal' => 6000,
            'status' => 'served',
        ]);
        Payment::create([
            'order_id' => $order1->id,
            'payment_method' => 'cash',
            'amount' => 30600,
            'status' => 'success',
            'paid_at' => now()->subMinutes(60),
        ]);
        LoyaltyLog::create([
            'user_id' => $customer->id,
            'order_id' => $order1->id,
            'type' => 'earned',
            'points' => 3,
            'balance_after' => 125,
            'description' => 'Poin transaksi ' . $order1->order_number,
        ]);

        // Order 2: Active Preparing (Muncul di KDS & POS)
        $table4 = Table::where('number', '04')->first();
        $order2 = Order::create([
            'order_number' => 'WD-' . now()->format('ymd') . '-0002',
            'table_id' => $table4->id,
            'customer_name' => 'Mas Andri',
            'customer_phone' => '081399887766',
            'type' => 'dine_in',
            'status' => 'preparing',
            'payment_status' => 'paid',
            'subtotal' => 41000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 41000,
            'paid_amount' => 41000,
            'change_amount' => 0,
            'payment_method' => 'qris',
            'notes' => 'Kopinya es dipisah ya mas',
            'cashier_id' => $cashier->id,
            'created_at' => now()->subMinutes(8),
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p7->id,
            'product_name' => 'Nasi Goreng Gila Spesial',
            'variant_options' => ['Pedas' => 'Sedang'],
            'unit_price' => 22000,
            'quantity' => 1,
            'subtotal' => 22000,
            'status' => 'preparing',
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p8->id,
            'product_name' => 'Pisang Goreng Keju Coklat Crispy',
            'variant_options' => [],
            'unit_price' => 14000,
            'quantity' => 1,
            'subtotal' => 14000,
            'status' => 'preparing',
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p4->id,
            'product_name' => 'Es Teh Manis Jumbo Warkop',
            'variant_options' => [],
            'unit_price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
            'status' => 'ready',
        ]);
        Payment::create([
            'order_id' => $order2->id,
            'payment_method' => 'qris',
            'amount' => 41000,
            'status' => 'success',
            'reference_number' => 'QRIS-' . time(),
            'paid_at' => now()->subMinutes(7),
        ]);

        // 11. Sample Expenses (Kas Kecil)
        Expense::create([
            'category' => 'operational',
            'title' => 'Beli Es Batu Kristal (3 Karung)',
            'description' => 'Beli di Agen Es Kristal Abadi untuk stok siang & malam',
            'amount' => 45000,
            'expense_date' => today(),
            'status' => 'approved',
            'recorded_by' => $cashier->id,
            'approved_by' => $owner->id,
        ]);
        Expense::create([
            'category' => 'raw_material',
            'title' => 'Restock Telur Ayam 5 Kg',
            'description' => 'Beli di Toko Madura depan warkop',
            'amount' => 140000,
            'expense_date' => today(),
            'status' => 'approved',
            'recorded_by' => $manager->id,
            'approved_by' => $owner->id,
        ]);
    }
}
