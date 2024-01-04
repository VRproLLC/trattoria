<?php

namespace App\Admin\Controllers;

use App\Models\CategoryPay;
use App\Models\Fop;
use App\Models\Organization;
use App\Models\Product\Category;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FopController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Фоп для приема платежей';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Fop());

        $grid->column('id', __('ID'));
        $grid->column('name', __('Имя'));
        $grid->column('code_id', __('Номер магазина'));
        $grid->column('category', __('Категории оплаты'))->display(function ($category) {
            return CategoryPay::get()->whereIn('id', $category)->pluck('name')->implode(', ');
        });

        $grid->column('organizations', __('Точки'))->display(function ($organizations) {
            return Organization::whereIn('id', $organizations)->pluck('fullName')->implode(', ');
        });
        $grid->column('is_active', __('Статус'))->bool();
        $grid->column('is_default', __('Транзитный фоп'))->bool();
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
    protected function detail($id): Show
    {
        $show = new Show(Fop::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('code_id', __('Code id'));
        $show->field('code_key', __('Code key'));
        $show->field('is_active', __('Is active'));
        $show->field('category', __('Category'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $form = new Form(new Fop());
        $form->text('name', __('Имя'))->required();
        $form->textarea('description', __('Описание'))->required();
        $form->text('code_id', __('ID мерчанта'))->required();
        $form->text('code_key', __('Ключ платежа'))->required();
        $form->switch('is_active', __('Статус'))->default(1);
        $form->switch('is_default', __('Транзитный фоп'))
            ->help('Транзитный фоп будет использоваться при оплате по умолчанию, только один может быть.')
            ->default(0);
        $form->multipleSelect('category', __('Категории оплаты'))->options(CategoryPay::get()->pluck('name', 'id'))->required();
        $form->multipleSelect('organizations', __('Точки'))->options(Organization::get()->pluck('fullName', 'id'))->required();

        return $form;
    }
}
