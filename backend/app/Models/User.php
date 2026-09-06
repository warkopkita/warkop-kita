<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'is_active',
        'loyalty_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'loyalty_points' => 'integer',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'recorded_by');
    }

    public function loyaltyLogs()
    {
        return $this->hasMany(LoyaltyLog::class);
    }

    // Role helpers
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['owner', 'manager']);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'cashier', 'barista', 'waiter']);
    }

    public function isCashier(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'cashier']);
    }

    public function isBarista(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'barista']);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
