<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'table_id',
        'customer_name',
        'customer_phone',
        'type',
        'status',
        'payment_status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'notes',
        'cashier_id',
        'served_by',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function server()
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('ymd');
        $lastOrder = self::whereDate('created_at', today())->latest('id')->first();
        $sequence = $lastOrder ? ((int) substr($lastOrder->order_number, -4)) + 1 : 1;
        return 'WD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
