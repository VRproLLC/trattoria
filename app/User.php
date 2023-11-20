<?php

namespace App;

use App\Models\Order\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function routeNotificationForOneSignalAndroid()
    {
        return $this->onsignal_token;
    }

    public function routeNotificationForOneSignal()
    {
        return $this->onsignal_token;
    }

    public function orders(){
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function order(){
        return $this->hasOne(Order::class, 'user_id', 'id')
            ->where('order_status', \App\Enums\OrderEnum::$GIV_AWAY)
            ->orderBy('created_at', 'desc');
    }
}
