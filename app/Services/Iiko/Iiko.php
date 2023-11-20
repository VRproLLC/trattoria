<?php

namespace App\Services\Iiko;

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


    public function closeOrder($order){

        $data = [
            "organizationId" => $this->api->organization,
            "orderId" => $order->iiko_id,
        ];

        return $this->api->closeOrder($data);
    }

    public function addOrder($cart)
    {
        $user = auth()->user();

        $items = [];
        $comment = '';
        if (!empty($cart->comment)) {
            $comment = $cart->comment;
        }

        $total = $cart->full_price;

        foreach ($cart->items as $cartItem) {
            $item = [
                "productId" => $cartItem->product->iiko_id,
                "amount" => $cartItem->amount,
                "type" => "Product",
//                "sum" => $cartItem->price_per_one * $cartItem->amount
            ];

            if(isset($cartItem->comment)) {
                $item['comment'] = $cartItem->comment;
            }

            array_push($items, $item);
        }

        $data = [
            "organizationId" => $this->api->organization,
            "terminalGroupId" => $this->api->getTerminals()->terminalGroups[0]->items[0]->id,

            "order" => [
                "id" => $cart->guid,

                "phone" => $user->phone,
                "orderTypeId" => "5b1508f9-fe5b-d6af-cb8d-043af587d5c2",
                "personsCount" => $cart->number_of_devices,
                "items" => $items,

                "customer" => [
//                "id" => $user ? @$user->guid : null,
                    "name" => $user->name,

                    "type" => "regular"
                ],
                "phone" => $user->phone,

            ]
        ];
        if ($cart->time_after == 2) {
            $data['order']["completeBefore"] = $cart->date . " " . $cart->time . ":00.000";
        }
        $paymentType = [
            "id" => $cart->payment_type->iiko_id,
            "code" => $cart->payment_type->code
        ];

        $paymentItems = [
            [
                "sum" => $total,
                "paymentType" => $paymentType,
                "isProcessedExternally" => false,
                "isExternal" => false
            ]
        ];

        $data['order']['comment'] = $comment;
        $data['order']['paymentItems'] = $paymentItems;

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
