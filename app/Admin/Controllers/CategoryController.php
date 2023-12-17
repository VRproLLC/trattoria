<?php

namespace App\Admin\Controllers;

use App\Models\Language;
use App\Models\Organization;
use App\Models\Product\Category;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Категории';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', __('#'));
        $grid->column('name', __('Название'));
        $grid->column('organization.name', __('Организация'));
        $grid->column('iiko_id', __('Iiko id'));
        $grid->column('isIncludedInMenu', __('Отображать в меню'))->switch();
        $grid->column('sort', __('Сортировка'))->editable();
        $grid->column('parentGroup', __('Родительская категория'));
        $grid->column('created_at', __('Дата создания'))->display(function ($created_at) {
            return Carbon::parse($created_at)->format('d.m.Y H:i:s');
        });
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('organization_id', __('Organization id'));
        $show->field('iiko_id', __('Iiko id'));
        $show->field('isDeleted', __('IsDeleted'));
        $show->field('isIncludedInMenu', __('IsIncludedInMenu'));
        $show->field('sort', __('Sort'));
        $show->field('parentGroup', __('ParentGroup'));
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
        $form = new Form(new Category());

        $form->select('organization_id', __('Организация'))->options(Organization::pluck('address', 'id'));
        $form->text('iiko_id', __('Iiko id'));
        $form->switch('isDeleted', __('Удалено'));
        $form->switch('isIncludedInMenu', __('Отображать в меню'));
        $form->text('sort', __('Сортировка'))->default(0);
        $form->text('parentGroup', __('Родительская категория'));
        $form->hasMany('translations', 'Локализация', function (Form\NestedForm $form){
            $form->select('language_id', 'Язык')->options(Language::all()->pluck('name', 'id'))->required();
            $form->text('name', 'Название')->required();
        })->mode('table');
        return $form;
    }
}
