<?php

namespace App\Admin\Controllers;

use App\Models\Organization;
use App\Models\PaymentType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Типы оплаты';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentType());
        $grid->model()
            ->orderBy('id', 'desc');

        $grid->column('id', __('#'));
        $grid->column('organization.address', __('Организация'));
        $grid->column('code', __('Код'));
        $grid->column('name', __('Имя'));
        $grid->column('isDeleted', __('Удалено'))->switch();

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
        $show = new Show(PaymentType::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('organization_id', __('Organization id'));
        $show->field('iiko_id', __('Iiko id'));
        $show->field('code', __('Code'));
        $show->field('name', __('Name'));
        $show->field('comment', __('Comment'));
        $show->field('combinable', __('Combinable'));
        $show->field('applicableMarketingCampaigns', __('ApplicableMarketingCampaigns'));
        $show->field('isDeleted', __('IsDeleted'));
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
        $form = new Form(new PaymentType());

        $form->select('organization_id', __('Организация'))->options(Organization::pluck('address', 'id'));

        $form->text('iiko_id', __('Iiko id'));
        $form->text('code', __('Код'));
        $form->text('name', __('Имя'));
        $form->textarea('comment', __('Комментарий'));
        $form->text('combinable', __('Компинированое'));
        $form->text('applicableMarketingCampaigns', __('ApplicableMarketingCampaigns'));
        $form->switch('isDeleted', __('Удалено'));

        return $form;
    }
}
