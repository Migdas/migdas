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
        'shipping_name',
        'shipping_cost',
        'pickup_point',
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

    /**
     * Nazwa dostawy do wyświetlenia (starsze zamówienia nie mają shipping_name).
     */
    public function shippingLabel(): string
    {
        return $this->shipping_name ?: match ($this->shipping_method) {
            'courier' => 'Kurier',
            'pickup' => 'Odbiór osobisty',
            default => (string) $this->shipping_method,
        };
    }

    public function isDeliveredToAddress(): bool
    {
        return blank($this->pickup_point) && $this->shipping_method !== 'pickup';
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
