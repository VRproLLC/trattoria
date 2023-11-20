<?php

namespace App\Admin\Controllers;

use App\Models\IikoAccount;
use App\Models\Organization;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrganizationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Organization';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Organization());

        if(Admin::user()->isRole('manager')) {
           $grid->model()->where('id', Admin::user()->organization_id);
        }

        $grid->column('id', __('#'));
        $grid->column('account.description', __('Аккаунт'));
        $grid->column('isActive', __('Отображение'))->switch();
        $grid->column('is_auto_work', __('Авто отображение'))->switch();
        $grid->column('fullName', __('Имя'));
        $grid->column('address', __('Адрес'));
        $grid->column('organizationType', __('Тип'));
        $grid->column('workTime', __('Время работы'));
        $grid->column('email', __('Email'));
        $grid->column('location', __('Локация'));
        $grid->column('phone', __('Телефон'));

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
        $show = new Show(Organization::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ikko_account_id', __('Ikko account id'));
        $show->field('iiko_id', __('Iiko id'));
        $show->field('isActive', __('IsActive'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('fullName', __('FullName'));
        $show->field('description', __('Description'));
        $show->field('address', __('Address'));
        $show->field('name', __('Name'));
        $show->field('organizationType', __('OrganizationType'));
        $show->field('timezone', __('Timezone'));
        $show->field('workTime', __('WorkTime'));
        $show->field('email', __('Email'));
        $show->field('location', __('Location'));
        $show->field('phone', __('Phone'));
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
        $form = new Form(new Organization());

        $form->select('ikko_account_id', __('Ikko account id'))->options(IikoAccount::pluck('description', 'id'));
        $form->text('iiko_id', __('Iiko id'));
        $form->switch('isActive', __('IsActive'));
        $form->switch('is_auto_work', __('Авто включение и выключение'));
        $form->text('latitude', __('Latitude'));
        $form->text('longitude', __('Longitude'));
        $form->text('fullName', __('FullName'));
        $form->textarea('description', __('Description'));
        $form->textarea('address', __('Address'));
        $form->text('name', __('Name'));
        $form->text('organizationType', __('OrganizationType'));
        $form->text('timezone', __('Timezone'));
        $form->text('workTime', __('WorkTime'));
        $form->email('email', __('Email'));
        $form->text('location', __('Location'));
        $form->text('phone', __('Phone'));

        $form->image('images', __('Фото'))->uniqueName()->required();

        return $form;
    }
}
