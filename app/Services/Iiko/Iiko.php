<?php

namespace App\Services\Iiko;

use App\Enums\OrderEnum;
use App\Models\Order\Order;
use App\Models\Product\Category;
use Illuminate\Support\Facades\Log;

class Iiko
{
    protected $api = '';
    protected $apiCard = '';

    public function __construct($login, $secret, $organization)
    {
        $this->api = new IikoApi($login, $secret, $organization);
        $this->apiCard = new IikoCardApi($login, $secret, $organization);
    }


    public function orderTypes($organizationIds){

        return $this->api->orderTypes([
            "organizationIds" => [$organizationIds],
        ]);
    }

    public function addOrderItems(
        Order $cart
    )
    {
        $items = [];

        foreach ($cart->items->where('is_status', 0) as $cartItem) {
            $item = [
                "productId" => $cartItem->product->iiko_id,
                "amount" => $cartItem->amount,
                "price" => $cartItem->product->price,
                "type" => "Product",
            ];
            if(isset($cartItem->comment)) {
                $item['comment'] = $cartItem->comment;
            }
            $cartItem->is_status = 1;
            $cartItem->save();

            $items[] = $item;
        }

        $data = [
            "organizationId" => $this->api->organization,
            "orderId" => $cart->iiko_id,
            "items" => $items
        ];

        return $this->api->addOrderItems($data);
    }

    public function updateOrderStatus(
        Order $order
    ){
        $data = [
            "organizationId" => $this->api->organization,
            "orderId" => $order->iiko_id,
        ];

        return $this->api->updateStatusOrder($data);
    }

    public function cancelConfirm(
        Order $order
    ){
        $data = [
            "organizationId" => $this->api->organization,
            "orderId" => $order->iiko_id,
        ];

        return $this->api->cancelConfirm($data);
    }

    public function getStatus(
        Order $order
    ){

        $data = [
            "organizationId" => '1eaa052d-153d-4245-98b6-5b15c62c59e6',
            "correlationId" => $order->iiko_id,
        ];

        return $this->api->getStatus($data);
    }


    public function closeOrder($order){

        $data = [
            "organizationId" => $this->api->organization,
            "orderId" => $order->iiko_id,
        ];

        return $this->api->closeOrder($data);
    }

    public function addOrder(
        Order $cart,
        bool $isProcessedExternally= false,
        bool $isPreliminary = false,
        bool $isExternal = false,
        bool $isPrepay = false
    )
    {
        $items = [];

        foreach ($cart->items as $cartItem) {
            $item = [
                "productId" => $cartItem->product->iiko_id,
                "amount" => $cartItem->amount,
                "type" => "Product",
            ];

            if(isset($cartItem->comment)) {
                $item['comment'] = $cartItem->comment;
            }

            $items[] = $item;
        }

        $data = [
            "organizationId" => $this->api->organization,
            "terminalGroupId" => $this->api->getTerminals()->terminalGroups[0]->items[0]->id,
            "order" => [
                "id" => $cart->uuid,
                "phone" => $cart->user->phone,
                "orderTypeId" => "5b1508f9-fe5b-d6af-cb8d-043af587d5c2",
                "personsCount" => $cart->number_of_devices,
                "items" => $items,
                "customer" => [
                    "name" => $cart->user->name,
                    "type" => "regular"
                ],
            ]
        ];

//        if ($cart->organization->delivery_types !== null){
//            $delivery = $cart->organization->delivery_types
//                ->where('orderServiceType', OrderEnum::$DELIVERY[$cart->is_delivery])
//                ->first();
//
//            if(isset($delivery->id)){
//                $data["order"]["orderTypeId"] = $delivery->uuid;
//            }
//        }

//        if($cart->is_delivery == 1) {
//            $data["order"]["orderServiceType"] = 'DeliveryByCourier';
//        } else {
//            $data["order"]["orderTypeId"] = '5b1508f9-fe5b-d6af-cb8d-043af587d5c2';
//        }

        if ($cart->is_time == 2 && $cart->date && $cart->time && $cart->time != '00:00') {
            $data['order']["completeBefore"] = $cart->date . " " . $cart->time . ":00.000";
        }

        $data['order']['comment'] = $cart->comment ?? '';
        $data['order']['payments'] = [
            [
                "sum" => $cart->full_price,
                "paymentTypeId" => $cart->payment_type->iiko_id,
                "paymentTypeKind" => $cart->payment_type->code,
                "isProcessedExternally" => $isProcessedExternally,
                "isExternal" => $isExternal,
                "isPreliminary" => $isPreliminary,
                "isPrepay" =>  $isPrepay
            ]
        ];

        return $this->api->sendOrder($data);
    }

    public function getOrganizationList()
    {
        return json_decode($this->api->getOrganizationList(), true);
    }

    public function getCitiesAndStreetsList()
    {
        return json_decode($this->api->getCitiesAndStreetsList(), true);
    }

    public function getOrder($guid)
    {
        return $this->api->getOrder($guid);
    }

    public function getOrdersByUser($guid)
    {
        return json_decode($this->api->getOrdersByUser($guid), true);
    }

    public function getOrdersByPhone($phone)
    {
        return json_decode($this->api->getOrdersByPhone($phone), true);
    }

    public function getOrdersByDate($from, $to)
    {
        return json_decode($this->api->getOrdersByDate($from, $to), true);
    }

    public function getClientInfo($guid)
    {
        return json_decode($this->apiCard->getClientInfo($guid), true);
    }

    public function getClientInfoByPhone($phone)
    {
        return json_decode($this->apiCard->getClientInfoByPhone($phone), true);
    }

    public function getDiscounts()
    {
        return json_decode($this->api->getDiscounts(), true);
    }

    public function getManualCondition()
    {
        return json_decode($this->apiCard->getManualCondition(), true);
    }

    public function getOrganizationProgramm()
    {
        return json_decode($this->apiCard->getOrganizationProgramm(), true);
    }

    public function getAllProducts()
    {
        return json_decode($this->api->getProducts(), true);
    }

    public function changeClientBalance($sum)
    {
        $action = 'refill_balance';
        if ($sum < 0) {
            $action = 'withdraw_balance';
            $sum = abs($sum);
        }
        $request = [
            'customerId' => auth()->user()->guid,
            'organizationId' => $this->api->organization,
            'walletId' => auth()->user()->walletId,
            'sum' => $sum
        ];

        return $this->apiCard->sendNewBalance($request, $action);
    }

    public function getPaymentTypes()
    {
        return json_decode($this->apiCard->getPaymentTypes(), true);
    }

    public function getDiscountTypes()
    {
        return json_decode($this->api->getDiscountTypes(), true);
    }

    public function getProgramms()
    {
        return json_decode($this->apiCard->getProgramms(), true);
    }

}
