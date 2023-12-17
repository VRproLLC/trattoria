<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPay extends Model
{
    protected $fillable = [
        'name',
        'iiko_id',
        'isDeleted',
        'organization_id',
    ];
}
