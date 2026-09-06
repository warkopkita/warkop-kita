<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = Table::with('activeOrder')->orderBy('number')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:tables,number',
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $num = $request->number;
        Table::create([
            'number' => $num,
            'name' => $request->name ?? 'Meja ' . $num,
            'qr_code' => 'WD-MEJA-' . strtoupper($num),
            'status' => 'available',
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil ditambahkan!');
    }

    public function updateStatus(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $table->status = $request->status;
        $table->save();

        return back()->with('success', 'Status meja diperbarui.');
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
