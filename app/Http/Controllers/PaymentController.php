<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\Organization;
use App\Services\Iiko\Iiko;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Обработка платежей
 *
 * Class PaymentController
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    const ORDER_APPROVED = 'approved';
    const ORDER_DECLINED = 'declined';
    const ORDER_EXPIRED = 'expired';


    /**
     * @param Request $request
     * @return JsonResponse
     */
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

                        $orderData->payment->update([
                            'status' => 1,
                            'payments' => $order
                        ]);

                        $this->createdOrder($orderData);
                        break;

                    case self::ORDER_DECLINED:
                        $orderData->update([
                            'payment_status' => 2
                        ]);
                        $orderData->payment->update([
                            'status' => 2,
                            'payments' => $order
                        ]);
                        break;

                    case self::ORDER_EXPIRED:
                        $orderData->update([
                            'payment_status' => 3
                        ]);
                        $orderData->payment->update([
                            'status' => 3,
                            'payments' => $order
                        ]);
                        break;

                    default:
                        $orderData->update([
                            'payment_status' => 4
                        ]);
                        $orderData->payment->update([
                            'status' => 4,
                            'payments' => $order
                        ]);
                }
            }
        }
        return response()->json([
            'response' => 'ok'
        ]);
    }

    /**
     * Создание заказа
     *
     * @param Order $order
     * @return void
     */
    private function createdOrder(
        Order $order
    ): void
    {
        $organization = Organization::where('id', $order->organization_id)->firstOrFail();

        $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
        $result = $iiko->addOrder($order, true, false, true, true)->orderInfo;

        if (isset($result->orderInfo->errorInfo) && $result->orderInfo->creationStatus == 'Error') {
            $order->update([
                'created_logs' => [
                    'status' => 'error',
                    'data' => (array) $result->orderInfo->errorInfo
                ]
            ]);
            return;
        }
        if (isset($result->error) || isset($result->errorDescription)) {
            $order->update([
                'created_logs' => [
                    'status' => 'error',
                    'data' => (array) $result
                ]
            ]);
            return;
        }
        $order->created_logs = [
            'created_logs' => [
                'status' => 'success',
                'data' => (array) $result->orderInfo
            ]
        ];
        $order->order_status = 1;
        $order->iiko_id = $result->id;
        $order->save();
    }
}
