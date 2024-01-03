<?php

namespace App\Http\Controllers;

use App\Models\Fop;
use App\Models\Order\Order;
use App\Models\Organization;
use App\Services\Iiko\Iiko;
use Cloudipsp\Configuration;
use Cloudipsp\Exception\ApiException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    const ORDER_APPROVED = 'approved';
    const ORDER_DECLINED = 'declined';
    const ORDER_EXPIRED = 'expired';
    const ORDER_PROCESSING = 'processing';
    const ORDER_CREATED = 'created';
    const ORDER_REVERSED = 'reversed';
    const ORDER_SEPARATOR = "_";


    public function webhook(
        Request $request
    )
    {
        $order = json_decode(base64_decode($request->get('data')), true);

        if (is_array($order) and isset($order['order'])) {
            $order = $order['order'];

            $orderData = Order::query()
                ->where('payment_status', 0)
                ->where('uuid', $order['product_id'])
                ->first();

            if($orderData){
                switch ($order['order_status']) {
                    case self::ORDER_APPROVED:
                        $orderData->update([
                            'payment_status' => 1
                        ]);
                        $this->createdOrder($orderData);
                        $this->payments($orderData, $order);
                        break;

                    case self::ORDER_DECLINED:
                        $orderData->update([
                            'payment_status' => 2
                        ]);
                        break;

                    case self::ORDER_EXPIRED:
                        $orderData->update([
                            'payment_status' => 3
                        ]);
                        break;

                    default:
                        $orderData->update([
                            'payment_status' => 4
                        ]);
                }
            }
        }
        return response()->json([
            'response' => 'ok'
        ]);
    }


    private function createdOrder(
        Order $order
    ): void
    {
        $organization = Organization::where('id', $order->organization_id)->firstOrFail();

        $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
        $result = $iiko->addOrder($order, true, false, true, true)->orderInfo;

        if (empty($result->id)) {
            return;
        }
        unset($order->time_after);

        $order->iiko_id = $result->id;
        $order->save();
    }

    public function payments(
        Order $orderData,
        array $order
    )
    {
        Configuration::setMerchantId('1537351');
        Configuration::setSecretKey('m2fFeTqLLHTWbvShDbYkNqSz7idhEjnx');
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
            } catch (ApiException $e) {
                Log::error('ApiException Error: ' . $e->getMessage());
            }
        }
    }
}
