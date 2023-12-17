<?php

namespace App\Admin\Controllers;

use App\Models\IikoAccount;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class IikoAccountController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Аккаунты iiko';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new IikoAccount());

        $grid->column('id', __('#'));
        $grid->column('description', __('Описание'));
        $grid->column('is_iiko', __('Эта точка использует Syrve'))->switch();

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
        $show = new Show(IikoAccount::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('description', __('Description'));
        $show->field('login', __('Login'));
        $show->field('password', __('Password'));
        $show->field('is_iiko', __('Is iiko'));
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
        $form = new Form(new IikoAccount());

        $form->text('description', __('Описание'));
        $form->text('login', __('Login'));
        $form->text('password', __('Password'));
        $form->switch('is_iiko', __('Эта точка использует iiko'))->default(1);

        return $form;
    }
}
