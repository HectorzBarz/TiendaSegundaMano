<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_phone',
        'billing_same_as_shipping',
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_province',
        'billing_postal_code',
        'billing_phone',
        'card_last_four',
        'card_brand',
    ];

    protected $casts = [
        'billing_same_as_shipping' => 'boolean',
        'total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
