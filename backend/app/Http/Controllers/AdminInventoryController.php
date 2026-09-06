<?php

namespace App\Http\Controllers;

use App\Models\MaterialStockLog;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminInventoryController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::with('stockLogs')->orderBy('name')->get();
        $recentLogs = MaterialStockLog::with(['rawMaterial', 'user'])->latest()->limit(15)->get();

        return view('admin.inventory.index', compact('materials', 'recentLogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:raw_materials,sku',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock_alert' => 'required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $material = RawMaterial::create([
            'name' => $request->name,
            'sku' => $request->sku ?? 'MAT-' . strtoupper(substr($request->name, 0, 3)) . '-' . rand(10, 99),
            'unit' => $request->unit,
            'current_stock' => $request->current_stock,
            'min_stock_alert' => $request->min_stock_alert,
            'cost_per_unit' => $request->cost_per_unit ?? 0,
            'supplier' => $request->supplier,
        ]);

        if ($request->current_stock > 0) {
            MaterialStockLog::create([
                'raw_material_id' => $material->id,
                'type' => 'in',
                'quantity' => $request->current_stock,
                'stock_before' => 0,
                'stock_after' => $request->current_stock,
                'reference' => 'Stok Awal',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:in,out,adjustment,waste',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $material = RawMaterial::findOrFail($id);
            $before = $material->current_stock;
            $qty = (float) $request->quantity;

            if ($request->type === 'in') {
                $after = $before + $qty;
            } elseif ($request->type === 'out' || $request->type === 'waste') {
                $after = max(0, $before - $qty);
            } else {
                $after = $qty; // Direct adjustment
            }

            $material->current_stock = $after;
            $material->save();

            MaterialStockLog::create([
                'raw_material_id' => $material->id,
                'type' => $request->type,
                'quantity' => $qty,
                'stock_before' => $before,
                'stock_after' => $after,
                'reference' => 'Penyesuaian Manual',
                'notes' => $request->notes,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('admin.inventory.index')->with('success', 'Stok bahan berhasil disesuaikan.');
        });
    }
}
