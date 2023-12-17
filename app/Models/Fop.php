<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fop extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code_id',
        'code_key',
        'is_active',
        'category'
    ];

    protected $casts = [
        'category' => 'json',
        'organizations' => 'json',
    ];
}
