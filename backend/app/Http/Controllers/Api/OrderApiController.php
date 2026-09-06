<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Table;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with(['items.product', 'table', 'payments'])->latest();

        if ($user && $user->role === 'customer') {
            $query->where('user_id', $user->id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_options' => 'nullable|array',
            'items.*.notes' => 'nullable|string',
            'type' => 'required|in:dine_in,takeaway',
            'table_id' => 'nullable',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'nullable|in:cash,qris,transfer,ewallet',
            'voucher_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $user = $request->user();
            $subtotal = 0;
            $orderItemsData = [];

            // Resolve table_id
            $resolvedTableId = null;
            if ($request->type === 'dine_in' && $request->table_id) {
                $tbl = Table::where('id', $request->table_id)->orWhere('number', $request->table_id)->first();
                $resolvedTableId = $tbl ? $tbl->id : Table::value('id');
            } elseif ($request->type === 'dine_in') {
                $resolvedTableId = Table::value('id');
            }

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unitPrice = $product->price;

                // Hitung tambahan harga dari varian jika ada
                if (!empty($item['variant_options'])) {
                    foreach ($item['variant_options'] as $vName => $vVal) {
                        if (is_array($vVal) && isset($vVal['price'])) {
                            $unitPrice += (float) $vVal['price'];
                        }
                    }
                }

                $itemSubtotal = $unitPrice * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'variant_options' => $item['variant_options'] ?? [],
                    'unit_price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                ];
            }

            // Voucher calculation
            $discountAmount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher && $voucher->isValidFor($subtotal)) {
                    $discountAmount = $voucher->calculateDiscount($subtotal);
                    $voucher->increment('used_count');
                }
            }

            // Tax calculation
            $taxPercent = (float) StoreSetting::get('tax_percentage', 0);
            $taxAmount = (($subtotal - $discountAmount) * $taxPercent) / 100;
            $totalAmount = max(0, $subtotal - $discountAmount + $taxAmount);

            // Create Order
            $orderNumber = Order::generateOrderNumber();
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user ? $user->id : null,
                'table_id' => $resolvedTableId,
                'customer_name' => $request->customer_name ?? ($user ? $user->name : 'Tamu'),
                'customer_phone' => $request->customer_phone ?? ($user ? $user->phone : null),
                'type' => $request->type,
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cash' ? 'unpaid' : ($request->payment_method ? 'paid' : 'unpaid'),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->payment_method && $request->payment_method !== 'cash' ? $totalAmount : 0,
                'change_amount' => 0,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'cashier_id' => $user && $user->isCashier() ? $user->id : null,
            ]);

            // Save items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Update Table Status if dine-in
            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'occupied']);
            }

            // If paid immediately (QRIS / Transfer)
            if ($order->payment_status === 'paid' && $order->payment_method) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method,
                    'amount' => $order->total_amount,
                    'status' => 'success',
                    'reference_number' => strtoupper($order->payment_method) . '-' . time(),
                    'paid_at' => now(),
                ]);

                // Loyalty points reward
                if ($user) {
                    $ptsRatio = (int) StoreSetting::get('loyalty_points_per_10k', 1);
                    $earnedPoints = floor($order->total_amount / 10000) * $ptsRatio;
                    if ($earnedPoints > 0) {
                        $user->increment('loyalty_points', $earnedPoints);
                        LoyaltyLog::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'type' => 'earned',
                            'points' => $earnedPoints,
                            'balance_after' => $user->loyalty_points,
                            'description' => 'Poin belanja order ' . $order->order_number,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'data' => $order->load(['items', 'table', 'payments']),
            ], 201);
        });
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'table', 'payments', 'cashier', 'server'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function track(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'table'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        if ($request->status === 'completed') {
            $order->completed_at = now();
            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
        } elseif ($request->status === 'cancelled') {
            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Status pesanan diubah menjadi {$order->status}.",
            'data' => $order,
        ]);
    }
}
