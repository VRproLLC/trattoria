<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $fillable = [
        'organization_id',
        'uuid',
        'name',
        'orderServiceType',
        'isDeleted',
        'externalRevision',
    ];
}
