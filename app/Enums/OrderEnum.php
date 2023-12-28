<?php

namespace App\Enums;


class OrderEnum
{
    public static $DELIVERY_YES = 1;
    public static $DELIVERY_NO = 2;

    public static $STATUSES = [
        0 => 'Наполняет корзину',
        1 => 'Новый заказ',
        2 => 'Готовится',
        3 => 'Готов',
        4 => 'Выдан',
        5 => 'Отменен',
        6 => 'Доставка',
    ];

    public static $IIKO_TRANSPORT_STATUSES = [
//        "Unconfirmed" => 1,
        "WaitCooking" => 1,
//        "ReadyForCooking" => 2,
        "CookingStarted" => 2,
        "CookingCompleted" => 3, // надо добиться этого статуса
//        "Waiting" => 3,
//        "OnWay" => 3,
//        "Delivered" => 4,
        "Closed" => 4,
        "Cancelled" => 5,
    ];

    public static $IIKO_TRANSPORT_WAIT_COOKING = 'WaitCooking';
    public static $IIKO_TRANSPORT_COOKING_STARTED = 'CookingStarted';
    public static $IIKO_TRANSPORT_COOKING_COMPLETED = 'CookingCompleted';
    public static $IIKO_TRANSPORT_CLOSED = 'Closed';
    public static $IIKO_TRANSPORT_CANCELLED = 'Cancelled';

    public static $IIKO_STATUSES = [
        'NEW' => 1,
        'CLOSED' => 3,
        'Готово' => 3
    ];

    public static $ACTIVE_STATUSES = [1, 2, 3, 4];

    public static $FILLS_ORDER = 0;
    public static $NEW_ORDER = 1;
    public static $IN_PROCESS = 2;
    public static $FINISHED = 3;
    public static $GIV_AWAY = 4;
    public static $CANCELED = 5;
    public static $DELIVERED = 6;


    public static function getStatus(int $statusId){
        $status = __('main.status');

        return $status[$statusId] ?? 'Status ' . $statusId;
    }

    public static $DELIVERY = [
        1 => 'DeliveryByCourier',
        2 => 'DeliveryPickUp',
    ];
}
