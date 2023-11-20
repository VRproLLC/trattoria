<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{

    protected $fillable = [
        'category_id',
        'language_id',
        'name',
        'description',
    ];

}
