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
use App\Notifications\OrderFinishNotification;
use App\Services\Iiko\Iiko;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Events\NewOrderEvent;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

class ClientsController extends Controller
{
    public function index(Content $content): Content
    {
        $users = User::orderBy('created_at', 'desc');

        if(\request('number')){
            $users->where('phone', 'like', '%' . request('number') .'%');
        }

        return $content
            ->title(sprintf('Клиенты %s', $users->count()))
            ->description('Список клиентов')
            ->row(view('admin.clients.main', [
                'users' => $users->paginate(20)
            ]));
    }

    public function show($id, Content $content)
    {
        $user = User::where('id', $id)->first();

        $orders = $user->orders()->orderBy('id', 'desc');

        if(\request('organization')){
            $orders->where('organization_id', \request('organization'));
        }
        if(\request('number')){
            $orders->where('iiko_order_number', \request('number'));
        }
        return $content
            ->title(sprintf('Пользователь %s', $user->name))
            ->description('Информация о пользователи')
            ->row(view('admin.clients.info', [
                'user' => $user,
                'organization' => Organization::get(),
                'orders' => $orders->get()
            ]));
    }

    public function order($id, $orderId, Content $content)
    {
        $user = User::where('id', $id)->first();

        $order = $user->orders()->where('id', $orderId)->first();

        $result = [];

        foreach ($order->items as $item) {
            if (array_key_exists($item->product_id, $result)) {
                $result[$item->product_id] = $result[$item->product_id] + $item->amount;
            } else {
                $result[$item->product_id] = $item->amount;
            }
        }
        return $content
            ->title(sprintf('Пользователь %s, заказ %s', $user->name, $order->iiko_order_number ?? 0))
            ->description('Информация о заказе')
            ->row(view('admin.clients.info_order', [
                'user' => $user,
                'result' => $result,
                'order' => $order,
                'products' => Product::get()
            ]));
    }
}
