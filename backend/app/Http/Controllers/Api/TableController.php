<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();

        return response()->json([
            'success' => true,
            'data' => $tables,
        ]);
    }

    public function getByQr(string $qrCode)
    {
        $table = Table::where('qr_code', $qrCode)->first();

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'QR Meja tidak valid.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $table->id,
                'number' => $table->number,
                'name' => $table->name,
                'qr_code' => $table->qr_code,
                'status' => $table->status,
                'capacity' => $table->capacity,
            ],
        ]);
    }
}
