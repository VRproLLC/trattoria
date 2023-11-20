<?php

namespace App\Models;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    protected $fillable = [
        'ikko_account_id',
        'iiko_id',
        'isActive',
        'latitude',
        'longitude',
        'fullName',
        'description',
        'address',
        'name',
        'organizationType',
        'timezone',
        'workTime',
        'email',
        'location',
        'phone',
    ];

    public function account()
    {
        return $this->belongsTo(IikoAccount::class, 'ikko_account_id');
    }

    public function payment_types()
    {
        return $this->hasMany(PaymentType::class, 'organization_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'organization_id');
    }

    public function getDeltaDistanceAttribute()
    {
        $user_cords = explode(',', \Cookie::get('geos'));

        if (count($user_cords) != 2) {
            return false;
        }

        $lat_1 = $user_cords[0];
        $lng_1 = $user_cords[1];

        $lat_2 = $this->attributes['latitude'];
        $lng_2 = $this->attributes['longitude'];

        $earth_radius = 6371;

        $pi80 = M_PI / 180;
        $lat_1 *= $pi80;
        $lng_1 *= $pi80;
        $lat_2 *= $pi80;
        $lng_2 *= $pi80;
        $dlat = $lat_2 - $lat_1;
        $dlon = $lng_2 - $lng_1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat_1) * cos($lat_2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earth_radius * $c, 2);
    }
}
