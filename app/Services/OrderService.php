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
     * @return bool
     */
    public function createdOrderToIiko(
        Order $order
    ): bool
    {
        $organization = Organization::where('id', Cookie::get('organization_id'))->firstOrFail();

        $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
        $result = $iiko->addOrder($order);

        if (!isset($result->orderInfo->id)) {
            return false;
        }

        $order->iiko_id = $result->orderInfo->id;
        $order->save();

        return true;
    }
}