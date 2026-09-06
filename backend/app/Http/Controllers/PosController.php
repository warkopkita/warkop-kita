<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Table;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->with('variants');
            }])
            ->orderBy('sort_order')
            ->get();

        $products = Product::where('is_active', true)
            ->with(['category', 'variants'])
            ->orderBy('is_favorite', 'desc')
            ->orderBy('name')
            ->get();

        $tables = Table::orderBy('number')->get();

        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->with(['items', 'table'])
            ->latest()
            ->limit(15)
            ->get();

        $storeSettings = [
            'name' => StoreSetting::get('store_name', 'Warkop Dadang'),
            'address' => StoreSetting::get('store_address', 'Jl. Pemuda No. 88, Jakarta Timur'),
            'phone' => StoreSetting::get('store_phone', '0812-3456-7890'),
            'tax_percentage' => (float) StoreSetting::get('tax_percentage', 0),
            'qris_account' => StoreSetting::get('qris_account_name', 'WARKOP DADANG NUSANTARA'),
        ];

        return view('pos.index', compact('categories', 'products', 'tables', 'activeOrders', 'storeSettings'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'type' => 'required|in:dine_in,takeaway',
            'table_id' => 'nullable|required_if:type,dine_in|exists:tables,id',
            'payment_method' => 'required|in:cash,qris,transfer,ewallet',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unitPrice = $product->price;

                if (!empty($item['variant_options'])) {
                    foreach ($item['variant_options'] as $vName => $vVal) {
                        if (is_array($vVal) && isset($vVal['price'])) {
                            $unitPrice += (float) $vVal['price'];
                        }
                    }
                }

                $itemSubtotal = $unitPrice * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'variant_options' => $item['variant_options'] ?? [],
                    'unit_price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                    'status' => 'preparing',
                ];
            }

            $discount = (float) ($request->discount_amount ?? 0);
            $taxPercent = (float) StoreSetting::get('tax_percentage', 0);
            $tax = (($subtotal - $discount) * $taxPercent) / 100;
            $total = max(0, $subtotal - $discount + $tax);
            $paid = (float) $request->paid_amount;
            $change = max(0, $paid - $total);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->customer_id ?? null,
                'table_id' => $request->table_id,
                'customer_name' => $request->customer_name ?? 'Pelanggan Walk-in',
                'customer_phone' => $request->customer_phone,
                'type' => $request->type,
                'status' => 'preparing',
                'payment_status' => $paid >= $total ? 'paid' : 'unpaid',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => $change,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'cashier_id' => Auth::id(),
            ]);

            foreach ($itemsData as $it) {
                $order->items()->create($it);
            }

            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'occupied']);
            }

            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'data' => $order->load(['items', 'table', 'cashier']),
            ]);
        });
    }

    public function receipt($id)
    {
        $order = Order::with(['items.product', 'table', 'cashier'])->findOrFail($id);
        $storeSettings = [
            'name' => StoreSetting::get('store_name', 'Warkop Dadang'),
            'address' => StoreSetting::get('store_address', 'Jl. Pemuda No. 88, Jakarta Timur'),
            'phone' => StoreSetting::get('store_phone', '0812-3456-7890'),
            'tagline' => StoreSetting::get('store_tagline', 'Nongkrong Santai, Kopi Nikmat, Wi-Fi Kencang'),
            'wifi_ssid' => StoreSetting::get('store_wifi_ssid', 'WARKOP DADANG 5G'),
            'wifi_pass' => StoreSetting::get('store_wifi_pass', 'kopidandangjuara'),
        ];

        return view('pos.receipt', compact('order', 'storeSettings'));
    }
}
