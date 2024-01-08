<?php

namespace App\Services;

use App\Models\Order\Order;
use App\Models\Organization;
use App\Services\Iiko\Iiko;
use Illuminate\Support\Facades\Cookie;

class OrderService
{

    /**
     * @param Order $order
     * @return false|string[]
     */
    public function getStopList(
        Order $order
    )
    {
        if ($order->organization->account->is_iiko == 1) {

            $sync = new IikoService();
            $stop_list = $sync->stop_lists($order->organization);

            if ($stop_list != false) {
                foreach ($order->items as $order_item) {
                    if (array_key_exists($order_item->product->iiko_id, $stop_list) && $stop_list[$order_item->product->iiko_id] == 0) {
                        return [
                            'error' => sprintf('К сожалению товар "%s" закончился, удалите его из корзины и оформите заказ.', $order_item->product->name)
                        ];
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param Order $order
     * @return array
     */
    public function createdOrderToIiko(
        Order $order
    ): array
    {
        $organization = Organization::where('id', Cookie::get('organization_id'))->firstOrFail();

        $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
        $result = $iiko->addOrder($order);

        if (isset($result->orderInfo->errorInfo) && $result->orderInfo->creationStatus == 'Error') {
            return [
                'status' => 'error',
                'data' => (array) $result->orderInfo->errorInfo
            ];
        }

        if (isset($result->error) || isset($result->errorDescription)) {
            return [
                'status' => 'error',
                'data' => (array) $result
            ];
        }

        $order->iiko_id = $result->orderInfo->id;
        $order->save();

        return [
            'status' => 'success',
            'data' => (array) $result->orderInfo
        ];
    }
}
