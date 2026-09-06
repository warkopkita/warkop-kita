<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class KdsController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->with(['items.product', 'table'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kds.index', compact('orders'));
    }

    public function getOrdersApi()
    {
        $orders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->with(['items.product', 'table'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,served,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        if ($request->status === 'completed' || $request->status === 'cancelled') {
            $order->completed_at = now();
            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'data' => $order,
        ]);
    }

    public function updateItemStatus(Request $request, $itemId)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served',
        ]);

        $item = OrderItem::findOrFail($itemId);
        $item->status = $request->status;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Status item diperbarui.',
            'data' => $item,
        ]);
    }
}
