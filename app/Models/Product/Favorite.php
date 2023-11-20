<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    protected $fillable = [
        'user_id',
        'organization_id',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
