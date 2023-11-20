<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IikoAccount extends Model
{
    protected $fillable = [
        'description',
        'login',
        'password',
        'is_iiko',
    ];
}
