<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Events\NewOrderEvent;
use App\Http\Requests\OrderStoreRequest;
use App\Models\IikoAccount;
use App\Models\Organization;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderFinishNotification;
use App\Payments\Fondy;
use App\Services\Iiko\Iiko;
use App\Services\IikoService;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
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
    public $uuid;

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
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->first();

        return view('pages.order.index', compact('order', 'organization'));
    }


    public function payStatus()
    {
        return view('pages.order.pay_status');
    }


    public function fondy(int $id, Fondy $fondy)
    {
        $order = Order::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if($order){
            if ($order->organization->account->is_iiko == 1) {
                $sync = new IikoService();
                $stop_list = $sync->stop_lists($order->organization);

                if ($stop_list != false) {
                    foreach ($order->items as $order_item) {
                        if (array_key_exists($order_item->product->iiko_id, $stop_list) && $stop_list[$order_item->product->iiko_id] == 0) {
                            return redirect()->back()->with(['error' => 'К сожалению товар "' . $order_item->product->name . '" закончился, удалите его из корзины и оформите заказ.']);
                        }
                    }
                }
            }

            $createdLink = $fondy->createdLink($order);

            if ($createdLink['status'] == 'success') {
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
    public function add_to_cart(AddToCartRequest $request)
    {
        $order = Order::where('uuid', $this->uuid)
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->firstOrCreate([
                'uuid' => $this->uuid,
                'user_id' => auth()->id(),
                'organization_id' => $this->organization_id
            ]);

        $order_item = OrderItem::where('order_id', $order->id)->where('product_id', $request->get('product_id'))->first();

        $product = Product::where('id', $request->get('product_id'))->firstOrFail();

        if (empty($order_item)) {
            $order_item = new OrderItem();
        }
        $order_item->order_id = $order->id;
        $order_item->product_id = $request->get('product_id');
        $order_item->amount = $request->get('amount');
        $order_item->price_per_one = $product->price;

        if (!empty($request->get('comment'))) {
            $order_item->comment = $request->get('comment');
        }

        $order_item->save();

        if ($request->get('amount') == 0) {
            $order_item->delete();
        }

        $this->calculate_full_price($order);

        return response()->json(['success' => true, 'total_amount' => $order->items->sum('amount'), 'full_price' => $order->full_price]);
    }


    public function calculate_full_price($order)
    {
        $full_price = 0;

        foreach ($order->items as $item) {
            $full_price += $item->price_per_one * $item->amount;
        }

        $order->full_price = $full_price;
        $order->save();

        return $full_price;
    }

    public function store(
        OrderStoreRequest $request
    )
    {


        $order = Order::where('uuid', $this->uuid)
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
        $order->time = date('H:i');

        if ($request->get('time_issue') && request('time') !== null) {
            $order->time = $request->get('time');
            $order->is_time = 2;
        }
        $order->save();

        if ($request->get('time_issue') == 2 && $request->get('time') === null) {
            return redirect()->back()->with(['error' => 'Укажите время доставки.']);
        }

        if ($order->organization->account->is_iiko == 1) {
            $sync = new IikoService();
            $stop_list = $sync->stop_lists($order->organization);

            if ($stop_list != false) {
                foreach ($order->items as $order_item) {
                    if (array_key_exists($order_item->product->iiko_id, $stop_list) && $stop_list[$order_item->product->iiko_id] == 0) {
                        return redirect()->back()->with(['error' => 'К сожалению товар "' . $order_item->product->name . '" закончился, удалите его из корзины и оформите заказ.']);
                    }
                }
            }
        }

        $order->order_status = 1;
        $order->save();

        if ($order->organization->account->is_iiko == 1) {
            $send_order_to_iiko = $this->send_order_to_iiko($order);
            if ($send_order_to_iiko == false) {
                Log::info('order crate error. Phone: ' . auth()->user()->phone);
                return redirect()->route('menu.index')->with(['error' => 'Произошла ошибка при создании заказа']);
            }
        }

        event(new NewOrderEvent(['action' => 'update_wrapper', 'is_need_sound' => true]));
        return redirect()->back()->with(['prevent_back' => true]);
    }


    public function comment()
    {
        $data = request()->validate([
            'id' => 'required|string|max:200',
            'comment' => 'nullable|string|max:200',
        ]);

        $order = OrderItem::where('id', request('id'))->firstOrFail();

        $order->comment = request('comment');
        $order->save();

        return response()->json(['success' => true]);
    }

    public function cancellation()
    {
        $data = request()->validate([
            'id' => 'required|exists:orders,id',
        ]);
        $order = Order::where('id', \request('id'))
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if($order->order_status == OrderEnum::$NEW_ORDER) {
            $organization = Organization::where('id', $order->organization_id)->firstOrFail();

            if ($organization) {
                if($organization->account->is_iiko == 1) {
                    $iiko = new Iiko($organization->account->login, $organization->account->password, $organization->iiko_id);
                    $data = $iiko->closeOrder($order);
                }
            }

            $order->update([
                'order_status' => OrderEnum::$CANCELED
            ]);

            return response()->json(['success' => 1, $data]);
        }
        return response()->json(['success' => 0]);
    }

    public function update()
    {
        $data = request()->validate([
            'payment_type' => 'required|exists:payment_types,id',
            'comment' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:200',
            'time' => 'nullable|string|max:200',
            'is_delivery' => 'required|integer',
            'time_issue' => 'required|integer',
        ]);
        $order = Order::where('uuid', $this->uuid)
            ->where('user_id', auth()->id())
            ->where('organization_id', $this->organization_id)
            ->firstOrFail();

        $order->payment_type_id = request('payment_type');
        $order->comment = request('comment');
        $order->is_delivery = request('is_delivery');
        $order->address = request('address');
        $order->is_time = request('time_issue');
        $order->time = request('time');
        $order->save();

        return response()->json(['success' => true]);
    }

    public function send_order_to_iiko(Order $order)
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

    public function update_status_from_iiko()
    {
        $orders = Order::whereNotNull('iiko_id')
            ->where('order_status', '<>', '4')
            ->where('order_status', '<>', '5')
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->where('created_at', '<=', Carbon::now()->endOfDay())
            ->get();

        foreach ($orders as $order) {
            $iiko = new Iiko($order->organization->account->login, $order->organization->account->password, $order->organization->iiko_id);
            $result = $iiko->getOrder($order->iiko_id);
//            if($order->iiko_id == '4fffeabe-37e7-48be-a106-cceb72ef724b'){
//                dd($result);
//            }
//            if($order->id == 224){
//                dd($result);
//            }
//            if(isset($result['status']) && $result['status'] == 'Готовится' && $order->order_status != OrderEnum::$IN_PROCESS){
//                $order->order_status = OrderEnum::$IN_PROCESS;
//                $order->save();
//
//                Notification::send($order->user, new InProgressNotification($order));
//                continue;
//            }
//
//            if($result['status'] == 'Готово' && $order->order_status != OrderEnum::$FINISHED){
//                $order->order_status = OrderEnum::$FINISHED;
//                $order->save();
//                Notification::send($order->user, new OrderFinishNotification());
//
//            }
//            if($result['status'] == 'Закрыта' && $order->order_status != OrderEnum::$GIV_AWAY){
//                $order->order_status = OrderEnum::$GIV_AWAY;
//                $order->save();
//            }
//            if($result['status'] == 'Отменена' && $order->order_status != OrderEnum::$CANCELED){
//                $order->order_status = OrderEnum::$CANCELED;
//                $order->save();
//            }

        }


        $products = Product::where('in_stop_list', 1)->get();

        foreach ($products as $product) {
            $product->in_stop_list = 0;
            $product->save();
        }

        $sync_controller = new IikoService();

        $organizations = Organization::whereHas('account', function ($q) {
            $q->where('is_iiko', 1);
        })->get();

        foreach ($organizations as $organization) {
            $stop_lists = $sync_controller->stop_lists($organization);
            if ($stop_lists != false) {
                foreach ($stop_lists as $iiko_id => $value) {
                    if ($value <= 0) {
                        $product = Product::where('iiko_id', $iiko_id)->first();
                        if ($product) {
                            $product->in_stop_list = 1;
                            $product->save();
                        }
                    }
                }
            }
        }
    }
}
