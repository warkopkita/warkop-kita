<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'table', 'cashier', 'server'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'table', 'payments', 'cashier', 'server'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
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

        return back()->with('success', 'Status pesanan berhasil diubah.');
    }
}
