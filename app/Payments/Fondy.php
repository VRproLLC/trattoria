<?php

namespace App\Payments;

use App\Models\Fop;
use App\Models\Order\Order;
use Cloudipsp\Api\Order\Settlements;
use Cloudipsp\Checkout;
use Cloudipsp\Configuration;
use Cloudipsp\Exception\ApiException;
use Cloudipsp\Subscription;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Fondy
{
    public function createdLink(Order $order): array
    {
        $transitFop = Fop::query()
            ->where('is_default', 1)
            ->latest()
            ->first();

        if(empty($transitFop)){
            return [
                'status' => 'error',
                'message' => 'Платежная система не настроена',
            ];
        }

        Configuration::setMerchantId($transitFop->code_id);
        Configuration::setSecretKey($transitFop->code_key);
        Configuration::setApiVersion('2.0');

        try {
            $data = [
                'order_desc' => 'Оплата замовлення в Trattoria',
                'currency' => 'UAH',
                'amount' => $order->full_price * 100,
                'response_url' =>  route('pay-status'),
                'server_callback_url' =>  route('webhook.fondy'),
                'product_id' => $order->uuid,
                'lifetime' => 36000,
                'merchant_data' => array(
                    'order_id' => $order->uuid,
                )
            ];
            $url = \Cloudipsp\Checkout::url($data);
            $data = $url->getData();

            return [
                'status' => 'success',
                'checkout_url' => $data['checkout_url'],
            ];

        } catch (ApiException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
