<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterUsers extends Model
{
    var $fillable = [
        'ua',
        'phone',
        'code',
        'ip'
    ];
}
