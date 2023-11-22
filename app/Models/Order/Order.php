<?php

namespace App\Models\Order;

use App\Enums\OrderEnum;
use App\Models\Organization;
use App\Models\PaymentType;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'organization_id',
        'uuid',
        'user_id',
        'order_status',
        'number_of_devices',
        'payment_type_id',
        'iiko_id',
        'comment',
        'timestamp_at',
        'iiko_order_number',
        'address',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'timestamp_at' => 'array',
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function payment_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getIikoOrderNumberAttribute()
    {
        if(!empty($this->attributes['iiko_order_number'])){
            return substr($this->attributes['iiko_order_number'], -3);
        }

        return '';
    }

    public function getOrderStatusTextAttribute()
    {
        if(isset(OrderEnum::$STATUSES[$this->attributes['order_status']])){
            return OrderEnum::getStatus($this->attributes['order_status']);
        }

        return '';
    }


    public function scopeRoleUser($q){
        if(!Admin::user()->isRole('administrator')) {
            $q->where('organization_id', Admin::user()->organization_id);
        }
        return $q;
    }
    public function scopeAdminDateFilter($q)
    {
        if(!empty(request('date_from'))){
            try {
                $q->where('created_at', '>', Carbon::createFromFormat('Y-m-d', request('date_from'))->startOfDay());
            }
            catch (\Exception $exception){
//                TODO make validation
            }
        } else $q->where('created_at', '>', Carbon::now()->startOfMonth());

        if(!empty(request('date_to'))){
            try {
                $q->where('created_at', '<', Carbon::createFromFormat('Y-m-d', request('date_to'))->startOfDay());
            }
            catch (\Exception $exception){
//                TODO make validation
            }
        } else $q->where('created_at', '<', Carbon::now()->endOfMonth());


        if(!empty(request('organization'))){
            $q->where('organization_id', request('organization'));
        }

        return $q;
    }


}
