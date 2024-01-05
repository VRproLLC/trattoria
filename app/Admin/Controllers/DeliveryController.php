<?php

namespace App\Admin\Controllers;

use App\Enums\OrderEnum;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Organization;
use Encore\Admin\Admin;
use Encore\Admin\Auth\Permission;

class DeliveryController extends Controller
{
    public function index()
    {
        if (Permission::isAdministrator()) {
            $organization = Organization::orderBy('id', 'desc')->get();
        } else $organization = Organization::where('id', Admin::user()->organization_id)->orderBy('id', 'desc')->get();

        $new_orders = Order::query()
            ->whereIn('organization_id', $organization->pluck('id'))
            ->where('order_status', OrderEnum::$NEW_ORDER)
            ->where('is_delivery', OrderEnum::$DELIVERY_YES)
            ->latest()
            ->get();

        $in_process_orders = Order::query()
            ->whereIn('organization_id', $organization->pluck('id'))
            ->where('order_status', OrderEnum::$IN_PROCESS)
            ->where('is_delivery', OrderEnum::$DELIVERY_YES)
            ->latest()
            ->get();

        $finished_orders = Order::query()
            ->whereIn('organization_id', $organization->pluck('id'))
            ->where('order_status', OrderEnum::$FINISHED)
            ->where('is_delivery', OrderEnum::$DELIVERY_YES)
            ->latest()
            ->get();

        $delivery_orders = Order::query()
            ->whereIn('organization_id', $organization->pluck('id'))
            ->where('order_status', OrderEnum::$DELIVERED)
            ->where('is_delivery', OrderEnum::$DELIVERY_YES)
            ->latest()
            ->get();

        return view('admin.delivery.index', compact('organization', 'new_orders', 'in_process_orders', 'finished_orders', 'delivery_orders'));
    }
}
