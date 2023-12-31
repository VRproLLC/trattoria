<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Events\NewOrderEvent;
use App\Http\Requests\Order\OrderCancellationRequest;
use App\Http\Requests\Order\OrderItemCommentRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Models\IikoAccount;
use App\Models\Organization;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\PaymentOrder;
use App\Models\Product\Product;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderFinishNotification;
use App\Payments\Fondy;
use App\Services\CalculatorService;
use App\Services\Iiko\Iiko;
use App\Services\IikoService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    /**
     * @var SessionManager|Store|mixed
     */
    public  $uuid;

    public $organization_id;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->uuid = session('uuid');
            $this->organization_id = Cookie::get('organization_id');
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $organization = Organization::where('id', Cookie::get('organization_id'))->first();//->firstOrFail();

        $order = Order::where('uuid', $this->uuid)
            ->with([
                'organization',
                'organization.delivery_types',
                'items',
                'payment_type'
            ])
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->first();

        return view('pages.order.index', compact('order', 'organization'));
    }


    public function payStatus()
    {
        return view('pages.order.pay_status');
    }

    /**
     * Создание онлайн заказа, подготовка ссылки для оплаты.
     *
     * @param int $id
     * @param Fondy $fondy
     * @param OrderService $service
     * @return Application|RedirectResponse|Redirector
     */
    public function fondy(
        int $id,
        Fondy $fondy,
        OrderService $service
    )
    {
        $order = Order::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if($order){
            $orderStopList = $service->getStopList($order);

            if(is_array($orderStopList)) {
                return redirect()->back()->with($orderStopList);
            }

            $createdLink = $fondy->createdLink($order);

            if ($createdLink['status'] == 'success') {
                PaymentOrder::create([
                    'public_id' => \Str::uuid(),
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'amount' => $order->full_price,
                ]);

                $order->update([
                    'order_status' => 1,
                    'payment_status' => 0,
                    'date' => Carbon::now(),
                ]);

                return redirect($createdLink['checkout_url']);
            }
        }
        return redirect()->route('menu.index')->with(['error' => 'Произошла ошибка при создании заказа']);
    }


    /**
     * @throws \Exception
     */
    public function basket()
    {
        $order = Order::where('uuid', $this->uuid)
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->first();

        foreach ($order->items as $item) {
            $item->delete();
        }

        $order->delete();

        return response()->json(['success' => true]);
    }


    /**
     * @throws \Exception
     */
    public function add_to_cart(
        AddToCartRequest $request,
        CalculatorService $calculatorService
    )
    {
        $order = Order::where('uuid', $this->uuid)
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->firstOrCreate([
                'uuid' => $this->uuid,
                'user_id' => auth()->id(),
                'organization_id' => $this->organization_id
            ]);

        $order_item = OrderItem::where('order_id', $order->id)
            ->where('product_id', $request->get('product_id'))
            ->first();

        $product = Product::where('id', $request->get('product_id'))
            ->firstOrFail();

        if (empty($order_item)) {
            $order_item = new OrderItem();
        }

        $order_item->order_id = $order->id;
        $order_item->product_id = $request->get('product_id');
        $order_item->amount = $request->get('amount');
        $order_item->price_per_one = $product->price;
        $order_item->is_status = 1;

        if ($request->get('comment') !== null) {
            $order_item->comment = $request->get('comment');
        }
        $order_item->save();

        if ($request->get('amount') == 0) {
            $order_item->delete();
        }

        $calculatorService->calculate_full_price($order);

        return response()->json([
            'success' => true,
            'total_amount' => $order->items->sum('amount'),
            'full_price' => $order->full_price
        ]);
    }

    /**
     * Сохранение заказа
     *
     * @param OrderStoreRequest $request
     * @param OrderService $service
     * @return RedirectResponse
     */
    public function store(
        OrderStoreRequest $request,
        OrderService $service
    )
    {
        $order = Order::where('uuid', $this->uuid)
            ->with([
                'organization.delivery_types',
                'organization'
            ])
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->firstOrFail();

        $order->number_of_devices = $request->get('number_of_devices');
        $order->payment_type_id = $request->get('payment_type');
        $order->date = date('Y-m-d');
        $order->comment = $request->get('comment');
        $order->timestamp_at = collect([
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        $order->address = $request->get('address');
        $order->is_delivery = $request->get('is_delivery');

        if ($request->get('time_issue') && request('time') !== null) {
            $order->time = $request->get('time');
            $order->is_time = 2;
        } else {
            $order->time = date('H:i');
        }
        $order->save();

        /**
         * Проверка на стоп лист
         */
        $orderStopList = $service->getStopList($order);

        if(is_array($orderStopList)) {
            return redirect()->back()->with($orderStopList);
        }

        if ($order->organization->account->is_iiko == 1) {
            $send_order_to_iiko = $service->createdOrderToIiko($order);

            $order->update([
                'created_logs' => $send_order_to_iiko
            ]);

            if ($send_order_to_iiko['status'] == 'error') {
                return redirect()->route('menu.index')->with(['error' => 'Произошла ошибка при создании заказа']);
            }

            $order->order_status = 1;
            $order->save();
        }

        event(new NewOrderEvent([
            'action' => 'update_wrapper',
            'is_need_sound' => true
        ]));

        return redirect()->back()->with([
            'prevent_back' => true
        ]);
    }


    /**
     * Комментарии к заказу (блюда).
     *
     * @param OrderItemCommentRequest $request
     * @return JsonResponse
     */
    public function comment(
        OrderItemCommentRequest $request
    )
    {
        $order = OrderItem::query()
            ->where('id', $request->get('id'))
            ->firstOrFail();

        $order->comment = $request->get('comment');
        $order->save();

        return response()->json(['success' => true]);
    }

    /**
     * Отмена заказа
     *
     * @param OrderCancellationRequest $request
     * @return JsonResponse
     */
    public function cancellation(
        OrderCancellationRequest $request
    )
    {
        $order = Order::where('id', $request->get('id'))
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if($order->order_status == OrderEnum::$NEW_ORDER) {
            $organization = Organization::where('id', $order->organization_id)->firstOrFail();

            if ($organization && $organization->account && $organization->account->is_iiko == 1) {
                $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
                $iiko->closeOrder($order);
            }

            $order->update([
                'order_status' => OrderEnum::$CANCELED
            ]);

            return response()->json([
                'success' => 1
            ]);
        }
        return response()->json([
            'success' => 0
        ]);
    }

    /**
     * Обновление данных заказа
     *
     * @param OrderUpdateRequest $request
     * @return JsonResponse
     */
    public function update(
        OrderUpdateRequest $request
    )
    {
        $order = Order::where('uuid', $this->uuid)
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->firstOrFail();

        $order->payment_type_id = $request->get('payment_type');
        $order->comment = $request->get('comment');
        $order->is_delivery = $request->get('is_delivery');
        $order->address = $request->get('address');
        $order->is_time = $request->get('time_issue');
        $order->time = $request->get('time');
        $order->delivery_price = $request->get('delivery_price');
        $order->save();

        return response()->json([
            'success' => true
        ]);
    }
}
