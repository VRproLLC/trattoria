<?php

namespace App\Services;

use App\Models\Order\Order;

class CalculatorService
{
    public function calculate_full_price(
        Order $order
    )
    {
        $full_price = 0;

        foreach ($order->items as $item) {
            $full_price += $item->price_per_one * $item->amount;
        }

        $order->full_price = $full_price;
        $order->save();

        return $full_price;
    }
}
