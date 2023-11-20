<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'product_id',
        'language_id',
        'name',
        'description',
    ];
}
