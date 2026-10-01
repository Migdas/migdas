<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number',
        'status',
        'payment_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'street',
        'building_number',
        'apartment_number',
        'postal_code',
        'city',
        'shipping_method',
        'shipping_cost',
        'products_total',
        'total',
        'customer_note',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'decimal:2',
            'products_total' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
