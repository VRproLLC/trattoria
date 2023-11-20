<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{

    protected $fillable = [
        'organization_id',
        'iiko_id',
        'code',
        'name',
        'comment',
        'combinable',
        'applicableMarketingCampaigns',
        'isDeleted',
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
