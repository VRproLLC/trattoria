<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    protected $fillable = ['user_id', 'title', 'values'];

    protected $casts = [
        'values' => 'array',
    ];
}
