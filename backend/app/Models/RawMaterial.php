<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'current_stock',
        'min_stock_alert',
        'cost_per_unit',
        'supplier',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'current_stock' => 'decimal:2',
            'min_stock_alert' => 'decimal:2',
            'cost_per_unit' => 'decimal:2',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock_alert;
    }

    public function stockLogs()
    {
        return $this->hasMany(MaterialStockLog::class);
    }
}
