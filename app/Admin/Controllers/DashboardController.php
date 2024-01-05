<?php

namespace App\Admin\Controllers;

use App\Enums\OrderEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Organization;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderCancellationNotification;
use App\Notifications\OrderFinishNotification;
use App\Services\CalculatorService;
use App\Services\Iiko\Iiko;
use Carbon\Carbon;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Events\NewOrderEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller
{
    public function index(Content $content): Content
    {
        if (Permission::isAdministrator()) {
            $organization = Organization::orderBy('id', 'desc')->get();
        } else $organization = Organization::where('id', Admin::user()->organization_id)->orderBy('id', 'desc')->get();

        return $content
            ->title('Заказы')
            ->description(' ')
            ->row('<h1>Выберите организацию</h1>')
            ->row('<p>Зеленый — организации БЕЗ iiko</p>')
            ->row(view('admin.order.dashboard', [
                'organization' => $organization
            ]));
    }

    public function show(int $id)
    {
        $organization = Organization::where('id', $id)->firstOrFail();

        $new_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$NEW_ORDER)->latest()->get();

        $in_process_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$IN_PROCESS)->latest()->get();

        $finished_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$FINISHED)->latest()->get();

        $delivery_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$DELIVERED)->latest()->get();

        return view('admin.order.index', compact('organization', 'new_orders', 'in_process_orders', 'finished_orders', 'delivery_orders'));
    }


    public function getArchive()
    {
        $data = request()->validate([
            'id' => 'required'
        ]);

        $orders = Order::where('organization_id', \request('id'))->orderBy('created_at', 'desc')->paginate(9);

        return view('partials.modals.admin_order_archive_box', [
            'orders' => $orders
        ]);
    }

    public function content()
    {
        $data = request()->validate([
            'id' => 'required'
        ]);

        $organization = Organization::where('id', $data['id'])->firstOrFail();
        $new_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$NEW_ORDER)->latest()->get();
        $in_process_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$IN_PROCESS)->latest()->get();
        $finished_orders = Order::where('organization_id', $organization->id)->where('order_status',
            OrderEnum::$FINISHED)->latest()->get();


        $delivery_orders = Order::query()
            ->where('organization_id', $organization->id)
            ->where('order_status', OrderEnum::$DELIVERED)
            ->where('is_delivery', OrderEnum::$DELIVERY_YES)
            ->latest()
            ->get();

        return view('admin.order.content', compact('organization', 'new_orders', 'in_process_orders', 'finished_orders', 'delivery_orders'));
    }

    public function archive()
    {
        $data = request()->validate([
            'id' => 'required'
        ]);

        $order = Order::where('id', $data['id'])->first();

        return view('partials.modals.admin_order_archive', [
            'order' => $order
        ]);
    }

    public function remove_order(): JsonResponse
    {
        request()->validate([
            'id' => 'required'
        ]);

        $order = Order::where('id', request('id'))->first();
        $order->update([
            'order_status' => OrderEnum::$CANCELED
        ]);

        $organization = Organization::where('id', $order->organization_id)->firstOrFail();

        if ($organization) {
            $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
            $data = $iiko->closeOrder($order);

            Notification::send($order->user, new OrderCancellationNotification($order));
        }

        return response()->json(['success' => 'true', 'order' => $data]);
    }

    public function detail()
    {
        $data = request()->validate([
            'id' => 'required'
        ]);
        $order = Order::where('id', $data['id'])->first();

        return view('partials.modals.admin_order_detail', compact('order'));
    }

    public function submit_order(): JsonResponse
    {
        $data = request()->validate([
            'id' => 'required'
        ]);
        $order = Order::where('id', $data['id'])->first();
        $order->order_status = OrderEnum::$IN_PROCESS;
        $order->timestamp_at = collect($order->timestamp_at)->merge([
            'in_process' => Carbon::now()->toDateTimeString()
        ]);
        $order->save();

        if ($order->organization !== null) {
            $iiko = new Iiko($order->organization->account->login, $order->organization->account->password, $order->organization->iiko_id);
            $data = $iiko->updateOrderStatus($order);
        }

        Notification::send($order->user, new InProgressNotification($order));

        event(new NewOrderEvent(['action' => 'update_wrapper']));

        return response()->json(['success' => 'true', $data ?? []]);
    }

    public function finish_order(): JsonResponse
    {
        $data = request()->validate([
            'id' => 'required'
        ]);
        $order = Order::where('id', $data['id'])->first();

        /**
         * Если заказ был, на доставку  то пишем что готов к доставке
         */
        if($order->is_delivery === 1 && OrderEnum::$IN_PROCESS === $order->order_status){
            $order->order_status = OrderEnum::$DELIVERED;
            $order->timestamp_at = collect($order->timestamp_at)->merge([
                'delivered' => Carbon::now()->toDateTimeString()
            ]);
            $order->save();
            return response()->json(['success' => 'true']);
        }

        $order->order_status = OrderEnum::$FINISHED;
        $order->timestamp_at = collect($order->timestamp_at)->merge([
            'finished' => Carbon::now()->toDateTimeString()
        ]);

        $order->save();

        if ($order->order_status == OrderEnum::$FINISHED) {
            Notification::send($order->user, new OrderFinishNotification());
        }
        event(new NewOrderEvent(['action' => 'update_wrapper']));

        return response()->json(['success' => 'true', $data ?? []]);
    }

    public function give_away_order(): JsonResponse
    {
        $data = request()->validate([
            'id' => 'required'
        ]);
        $order = Order::where('id', $data['id'])->first();
        $order->order_status = OrderEnum::$GIV_AWAY;
        $order->is_time = 0;
        $order->timestamp_at = collect($order->timestamp_at)->merge([
            'completion' => Carbon::now()->toDateTimeString()
        ]);
        $order->save();

        event(new NewOrderEvent(['action' => 'update_wrapper']));
        return response()->json(['success' => 'true', $data ?? []]);

    }

    public function edit()
    {
        $data = request()->validate([
            'id' => 'required'
        ]);

        return view('partials.modals.admin_order_edit', [
            'order' =>  Order::where('id', $data['id'])->first()
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(): JsonResponse
    {
        $data = request()->validate([
            'order_id' => 'required',
            'order_item' => 'required',
            'iiko_order_number' => 'required',
        ]);

        $order = Order::where('id', $data['order_id'])->firstOrFail();
        $order->iiko_order_number = $data['iiko_order_number'];
        $order->save();

        foreach ($data['order_item'] as $key => $amount) {
            $item = OrderItem::where('order_id', $data['order_id'])
                ->where('id', $key)
                ->first();

            if(isset($item->id)) {
                if ($amount == 0) {
                    $item->delete();
                } else {
                    if ($amount != $item->amount) {
                        $item->is_status = 0;
                    } else $item->is_status = 1;

                    $item->amount = $amount;
                    $item->save();
                }
            }
        }

        if(isset($order->id) && $order->items->count()) {
            $iiko = new Iiko($order->organization->account->login, $order->organization->account->password, $order->organization->iiko_id);
            $iiko->addOrderItems($order);
        }

        $service = new CalculatorService();
        $service->calculate_full_price($order);

        event(new NewOrderEvent([
            'action' => 'update_wrapper'
        ]));

        return response()->json(['success' => 'true']);
    }

    public function add_product()
    {
        $data = request()->validate([
            'id' => 'required',
            'organization' => 'required',
        ]);
        $order = Order::where('id', $data['id'])->first();

        $organization = Organization::where('id', $data['organization'])->firstOrFail();
        $categories = Category::where('organization_id', $organization->id)->where('isDeleted', 0)->where('isIncludedInMenu', 1)->orderBy('sort')->whereNull('parentGroup')->get();

        return view('partials.modals.admin_order_add_product', compact('order', 'categories'));
    }

    public function add_product_save(
        CalculatorService $service
    ): JsonResponse
    {
        $data = request()->validate([
            'order_id' => 'required',
            'product' => 'required',
        ]);

        $order = Order::where('id', $data['order_id'])->first();

        foreach ($data['product'] as $key => $value) {
            if ($value == 1) {
                $product = Product::where('id', $key)->firstOrFail();

                if(isset($product->id)) {
                    $order_item = OrderItem::where('order_id', $order->id)->where('product_id', $key)->first();

                    if (empty($order_item)) {
                        $order_item = new OrderItem();
                    }

                    $order_item->order_id = $order->id;
                    $order_item->product_id = $product->id;
                    $order_item->amount = 1;
                    $order_item->price_per_one = $product->price;
                    $order_item->save();
                }
            }
        }

        $service->calculate_full_price($order);

        event(new NewOrderEvent(['action' => 'update_wrapper']));

        return response()->json(['success' => 'true', 'order_id' => $order->id, 'href' => route('admin.dashboard.edit')]);
    }
}
