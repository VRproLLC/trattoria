<?php

namespace App\Admin\Controllers;

use App\Enums\PaymentEnum;
use App\Models\PaymentOrder;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Онлайн платежи.';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentOrder());
        $grid->disableActions()
            ->disableCreateButton();

        $grid->column('id', __('ID'));
        $grid->column('order', __('Номер заказа'))->display(function ($order) {
            if($order['iiko_order_number'] !== "") {
                return sprintf('%s (%s)', $order['id'], $order['iiko_order_number']) ?? 'Неизвестно';
            }
            return sprintf('%s', $order['id']) ?? 'Неизвестно';
        });
        $grid->column('user', __('Клиент'))->display(function ($user) {
            return sprintf('%s (%s)', $user['name'], $user['phone']) ?? 'Неизвестно';
        });
        $grid->column('amount', __('Сумма платежа'))->display(function ($amount) {
            return sprintf('%s гривен.', $amount) ?? 'Неизвестно';
        });
        $grid->column('status', __('Статус оплаты'))->display(function ($status) {
            return PaymentEnum::$STATUS[$status] ?? 'Неизвестно';
        });
        $grid->column('settlement', __('Расщепление'))->display(function ($settlement) {
            return $settlement ? 'Да' : 'Нет';
        });
        $grid->column('created_at', __('Создан'))->display(function ($created_at) {
            return Carbon::parse($created_at)->format('d.m.Y H:i:s');
        });
        $grid->column('updated_at', __('Обновлен'))->display(function ($updated_at) {
            return Carbon::parse($updated_at)->format('d.m.Y H:i:s');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(PaymentOrder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('public_id', __('Public id'));
        $show->field('order_id', __('Order id'));
        $show->field('user_id', __('User id'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'));
        $show->field('settlement', __('Settlement'));
        $show->field('payments', __('Payments'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PaymentOrder());

        $form->text('public_id', __('Public id'));
        $form->number('order_id', __('Order id'));
        $form->number('user_id', __('User id'));
        $form->decimal('amount', __('Amount'));
        $form->switch('status', __('Status'));
        $form->switch('settlement', __('Settlement'));
        $form->textarea('payments', __('Payments'));

        return $form;
    }
}
