<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update/Modify users table with Warkop specific fields
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('password'); // owner, manager, cashier, barista, waiter, customer
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->integer('loyalty_points')->default(0)->after('is_active');
        });

        // 2. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->string('image')->nullable();
            $table->integer('stock')->default(-1); // -1 = unlimited/made-to-order
            $table->boolean('is_active')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });

        // 4. Product Variants
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name'); // e.g. "Suhu", "Level Gula", "Topping"
            $table->string('type')->default('select'); // single, multiple, text
            $table->json('options'); // [{"name": "Dingin/Es", "price": 2000}, {"name": "Panas", "price": 0}]
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // 5. Tables (Meja Warkop)
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // "01", "02", "VIP-1", "Outdoor-3"
            $table->string('name')->nullable();
            $table->string('qr_code')->unique();
            $table->string('status')->default('available'); // available, occupied, reserved
            $table->integer('capacity')->default(4);
            $table->timestamps();
        });

        // 6. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('type', ['dine_in', 'takeaway'])->default('dine_in');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'served', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable(); // cash, qris, transfer, ewallet
            $table->text('notes')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('served_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 7. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name');
            $table->json('variant_options')->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->string('notes')->nullable();
            $table->enum('status', ['pending', 'preparing', 'ready', 'served'])->default('pending');
            $table->timestamps();
        });

        // 8. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method'); // cash, qris, transfer, ewallet
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('reference_number')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. Raw Materials (Bahan Baku)
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('unit'); // gram, ml, pcs, kg, sachet, cup
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->decimal('min_stock_alert', 12, 2)->default(10);
            $table->decimal('cost_per_unit', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. Material Stock Logs
        Schema::create('material_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'waste']);
            $table->decimal('quantity', 12, 2);
            $table->decimal('stock_before', 12, 2);
            $table->decimal('stock_after', 12, 2);
            $table->string('reference')->nullable(); // Order # or purchase inv
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 11. Shifts
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Pagi / Siang", "Malam", "Full Day"
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 12. Shift Assignments
        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('date');
            $table->timestamps();
        });

        // 13. Attendances (Absensi Geofence + Selfie)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('clock_in_photo')->nullable();
            $table->string('clock_out_photo')->nullable();
            $table->decimal('clock_in_latitude', 10, 8)->nullable();
            $table->decimal('clock_in_longitude', 11, 8)->nullable();
            $table->decimal('clock_out_latitude', 10, 8)->nullable();
            $table->decimal('clock_out_longitude', 11, 8)->nullable();
            $table->decimal('clock_in_distance_meters', 8, 2)->nullable();
            $table->decimal('clock_out_distance_meters', 8, 2)->nullable();
            $table->enum('status', ['present', 'late', 'leave', 'absent'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 14. Expenses (Pengeluaran Kas Kecil & Bon)
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['operational', 'raw_material', 'maintenance', 'utility', 'salary', 'cash_advance', 'other'])->default('operational');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('receipt_photo')->nullable();
            $table->date('expense_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 15. Loyalty Logs
        Schema::create('loyalty_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjustment']);
            $table->integer('points');
            $table->integer('balance_after');
            $table->string('description');
            $table->timestamps();
        });

        // 16. Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('discount_value', 12, 2);
            $table->decimal('min_purchase', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('points_required')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 17. Store Settings
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('loyalty_logs');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('shift_assignments');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('material_stock_logs');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar', 'is_active', 'loyalty_points']);
        });
    }
};
