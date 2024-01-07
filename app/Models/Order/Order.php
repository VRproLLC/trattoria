<?php

namespace App\Models\Order;

use App\Enums\OrderEnum;
use App\Models\Organization;
use App\Models\PaymentOrder;
use App\Models\PaymentType;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'is_delivery',
        'payment_status',
        'full_price',
        'date',
        'time',
        'deleted_at',
        'is_time',
        'created_logs'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'timestamp_at' => 'array',
        'created_logs' => 'json',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function payment_type(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PaymentOrder::class, 'order_id');
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
