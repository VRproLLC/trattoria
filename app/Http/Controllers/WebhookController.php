<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Organization;
use App\Models\Product\Product;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderCancellationNotification;
use App\Notifications\OrderFinishNotification;
use App\Services\CalculatorService;
use App\Services\IikoService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class WebhookController extends Controller
{

    public function get()
    {
        $request = request()->all();

        try {
            if (isset($request[0]['eventType'])) {
                if ($request[0]['eventType'] == 'DeliveryOrderUpdate') {
                    $event_info = $request[0]['eventInfo'];

                    if(isset($event_info['id'])) {
                        $order = Order::where('iiko_id', $event_info['id'])->first();
                        if (isset($event_info['order']['number'])) {
                            $order->iiko_order_number = $event_info['order']['number'];
                        }
                        $order->save();

                        $this->send_order_status_notification($event_info['id'], $event_info['order']['status']);

                        if(in_array($event_info['order']['status'], ['Unconfirmed', 'WaitCooking', 'ReadyForCooking', 'CookingStarted'])) {
                            $this->orderItemsUpdate($event_info);
                        }
                    }
                }

                if ($request[0]['eventType'] == 'StopListUpdate') {
                    $this->update_stop_list($request[0]['organizationId']);
                }

            }
        } catch (\Exception $exception) {
            Log::info('Webhook error: ' . $exception);
        }

        return response()->json('success', 200);
    }

    /**
     * Обновление данных заказа.
     *
     * @param $event
     * @return void
     */
    private function orderItemsUpdate($event)
    {
        $order = Order::where('iiko_id', $event['id'])->first();

        if(count($event['order']['items'])){
            foreach ($event['order']['items'] as $item){
                $orderItem = OrderItem::query()
                    ->where('order_id', $order->id)
                    ->whereHas('product', function (Builder $query) use ($item) {
                        $query->where('iiko_id', $item['product']['id']);
                    })
                    ->first();

                if(empty($orderItem)){

                    $productInfo = Product::query()->where('iiko_id', $item['product']['id'])->first();

                    if(isset($productInfo)) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'comment' => $item['comment'],
                            'product_id' => $productInfo->id,
                            'amount' => $item['amount'],
                            'price_per_one' => $item['price'],
                            'is_status' => 1
                        ]);
                    }
                } else {
                    $orderItem->update([
                        'is_status' => 1,
                        'amount' => $item['amount'],
                        'price_per_one' => $item['price'],
                        'comment' => $item['comment'],
                    ]);
                }
            }

            (new CalculatorService())->calculate_full_price($order);
        }
    }

    /**
     * Обновление статуса
     *
     * @param $order_id
     * @param $status
     * @return bool
     */
    public function send_order_status_notification($order_id, $status)
    {
        $order = Order::where('iiko_id', $order_id)->first();

        if (empty($order)) {
            return false;
        }

        if ($status == OrderEnum::$IIKO_TRANSPORT_COOKING_STARTED) {
            $order->order_status = OrderEnum::$IN_PROCESS;
            $order->timestamp_at = collect($order->timestamp_at)->merge([
                'in_process' => Carbon::now()->toDateTimeString()
            ]);
            $order->save();

            Notification::send($order->user, new InProgressNotification($order));
        }

        if ($status == OrderEnum::$IIKO_TRANSPORT_COOKING_COMPLETED) {
            $order->order_status = OrderEnum::$FINISHED;
            $order->timestamp_at = collect($order->timestamp_at)->merge([
                'finished' => Carbon::now()->toDateTimeString()
            ]);
            $order->save();

            Notification::send($order->user, new OrderFinishNotification());
        }

        if ($status == OrderEnum::$IIKO_TRANSPORT_CLOSED) {
            $order->order_status = OrderEnum::$GIV_AWAY;
            $order->is_time = 0;
            $order->timestamp_at = collect($order->timestamp_at)->merge([
                'completion' => Carbon::now()->toDateTimeString()
            ]);

            $order->save();
        }
        if ($status == OrderEnum::$IIKO_TRANSPORT_CANCELLED) {
            $order->order_status = OrderEnum::$CANCELED;
            $order->save();
            Notification::send($order->user, new OrderCancellationNotification());
        }

        return true;
    }

    /**
     * @param $organization_id
     * @return false|void
     */
    public function update_stop_list($organization_id)
    {
        $organization = Organization::where('iiko_id', $organization_id)->first();

        if (empty($organization)) {
            return false;
        }

        $products = Product::where('organization_id', $organization->id)->where('in_stop_list', 1)->get();

        foreach ($products as $product) {
            $product->in_stop_list = 0;
            $product->save();
        }

        $sync_controller = new IikoService();
        $stop_lists = $sync_controller->stop_lists($organization);

        if ($stop_lists != false) {
            foreach ($stop_lists as $iiko_id => $value) {
                if ($value <= 0) {
                    $product = Product::where('iiko_id', $iiko_id)
                        ->where('organization_id', $organization->id)
                        ->first();

                    if ($product) {
                        $product->in_stop_list = 1;
                        $product->save();
                    }
                }
            }
        }
    }
}
