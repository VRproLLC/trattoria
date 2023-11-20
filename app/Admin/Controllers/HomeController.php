<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Organization;
use App\Models\Product\Product;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Статистика ')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    if(Admin::user()->isRole('administrator')) {
                        $organization = Organization::get();
                    } else $organization = Organization::where('id', Admin::user()->organization_id)->get();

                    $column->append(new Box('Фильтр', view('admin.stats.filter', [
                        'organization' => $organization
                    ])));
                });

                $row->column(4, function (Column $column) {
                    $orders = Order::RoleUser()->AdminDateFilter()->where('orders.order_status', '<>', 0)->get();

                    $result = [];

                    foreach ($orders as $order) {
                        foreach ($order->items as $item) {
                            if (array_key_exists($item->product_id, $result)) {
                                $result[$item->product_id] = $result[$item->product_id] + $item->amount;
                            } else {
                                $result[$item->product_id] = $item->amount;
                            }
                        }
                    }
                    $column->append(new Box('Общее кол-во проданных позиций',
                        view('admin.stats.total_count', [
                            'result' =>$result,
                            'products' => Product::get()
                        ])));
                });

                $row->column(3, function (Column $column) {
                    $orders = Order::RoleUser()->AdminDateFilter()->where('orders.order_status', '<>', 0)->get();

                    $sum = 0;
                    $avg = [];

                    foreach ($orders as $key => $order) {
                        foreach ($order->items as $item) {
                            $sum += $item->price_per_one * $item->amount;

                            if(isset($avg[$key])){
                                $avg[$key] = $avg[$key] +$item->price_per_one * $item->amount;
                            } else $avg[$key] = $item->price_per_one * $item->amount;
                        }
                    }

                    if(count($avg) > 0) {
                        $result = round(array_sum($avg) / sizeof($avg));
                    } else $result = 0;

                    $column->append(new Box('Информация по заказам', view('admin.stats.total_sum', [
                        'sum' => $sum,
                        'agv'=> $result,
                        'orders_count' => $avg,
                        'end_orders' => $orders->last()
                    ])));
                });
            });
    }
}
