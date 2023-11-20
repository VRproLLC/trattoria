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
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Events\NewOrderEvent;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

class NotificationController extends AdminController
{
    protected $title = 'Уведомления в приложение';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new \App\Models\Notification());

        $grid->column('id', __('#'));
        $grid->column('subject', __('Заголовок'));
        $grid->column('text', __('Текст'));
        $grid->column('link', __('Ссылка'));
       // $grid->column('send_time', __('Время отправки'));
        $grid->column('is_status', __('Статус'))->display(function ($is_status){
            return ($is_status == 1) ? 'отправленное' : 'ждет отправки';
        });
        return $grid;
    }

    protected function form()
    {
        $form = new Form(new \App\Models\Notification());
        $form->text('subject', __('Заголовок'))->required();
        $form->textarea('text', __('Текст'))->required();
        $form->text('link', __('Ссылка'));
        $form->datetime('send_time', __('Время отправки'));

        return $form;
    }
}
