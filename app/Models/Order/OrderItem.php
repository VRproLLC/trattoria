<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'price_per_one',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
