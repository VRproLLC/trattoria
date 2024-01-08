<?php

namespace App\Payments;

use App\Models\Fop;
use App\Models\Order\Order;
use Cloudipsp\Api\Order\Settlements;
use Cloudipsp\Checkout;
use Cloudipsp\Configuration;
use Cloudipsp\Exception\ApiException;
use Cloudipsp\Subscription;
use Illuminate\Support\Facades\Log;
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


    /**
     * @param Order $orderData
     * @param array $order
     * @return void
     */
    public function settlement(
        Order $orderData,
        array $order
    )
    {
        $transitFop = Fop::query()
            ->where('is_default', 1)
            ->latest()
            ->first();

        if(empty($transitFop)){
            return;
        }

        Configuration::setMerchantId($transitFop->code_id);
        Configuration::setSecretKey($transitFop->code_key);
        Configuration::setApiVersion('2.0');

        $payData = [];

        foreach ($orderData->items as $item){
            $fop = Fop::query()
                ->where('is_active', 1)
                ->whereJsonContains('category', (string) $item->product->categoryPayId)
                ->first();

            if(isset($fop)){
                $payData[$fop->id][$item->id] = $item->product->price;
            }
        }

        $receiver = [];

        foreach ($payData as $key => $value){
            $receiver[] = [
                'requisites' => [
                    'amount' => array_sum($value) * 100,
                    'merchant_id' => Fop::find($key)->code_id,
                ],
                'type' => 'merchant'
            ];
        }

        if (count($receiver)) {
            $data = [
                'operation_id' => $order['order_id'],
                'currency' => 'UAH',
                'amount' => $order['amount'],
                'order_desc' => 'Разбитие счета',
            ];
            $data['receiver'] = $receiver;

            try {
                $paymentResponse = \Cloudipsp\Order::settlement($data);
                $paymentResponse->getData();

                $orderData->payment->update([
                    'settlement' => 1,
                ]);
            } catch (ApiException $e) {
                Log::error('ApiException Error: ' . $e->getMessage());
            }
        }
    }

}
