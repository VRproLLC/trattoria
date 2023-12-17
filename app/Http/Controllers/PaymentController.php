<?php

namespace App\Http\Controllers;

use App\Models\Fop;
use App\Models\Order\Order;
use Cloudipsp\Exception\ApiException;
use Illuminate\Support\Facades\Log;
use Request;

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
                ->where('uuid', $order['product_id'])
                ->first();

            if($orderData){
                switch ($order['order_status']) {
                    case self::ORDER_APPROVED:
                        $this->payments($orderData, $order['order_id']);
                        break;

                    case self::ORDER_DECLINED:

                        break;

                    case self::ORDER_EXPIRED:

                        break;

                    default:

                }
            }
        }
        return response()->json([
            'response' => 'ok'
        ]);
    }

    public function payments(
        Order $order,
        string $operation_id
    )
    {
        $payData = [];
        $payDateNot = [];

        foreach ($order->items as $item){
            $fop = Fop::whereJsonContains('category', (string) $item->product->categoryPayId)->first();

            if(isset($fop)){
                $payData[$fop->id][$item->id] = $item->product->price;
            } else {
                $payDateNot[] = [
                    'price' => $item->product->price,
                    'category' => $item->product->categoryPayId
                ];
            }
        }

        $receiver = [];

        foreach ($payData as $key => $value){
            $receiver[] = [
                'requisites' => [
                    'amount' => array_sum($value),
                    'merchant_id' => Fop::find($key)->code_id,
                ],
                'type' => 'merchant'
            ];
        }

        if (count($receiver)) {
            $data = [
                'order_id' => $order->uuid,
                'operation_id' => $operation_id,
                'currency' => 'UAH',
                'order_type' => 'settlement',
                'amount' => $order->full_price,
                'order_desc' => 'Разбитие счета',
                'response_url' => route('main'),
                'server_callback_url' => route('main'),
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
