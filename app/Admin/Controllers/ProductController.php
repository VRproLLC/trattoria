<?php

namespace App\Admin\Controllers;

use App\Models\Language;
use App\Models\Organization;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Translations\CategoryTranslation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Продукты';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Product());


        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->in('organization_id', 'Организация')->select(Organization::pluck('name', 'id'));

            $categories = Category::get();
            $categories_options = [];
            foreach ($categories as $category){
                $organization_name = 'no organization';
                if($category->organization){
                    $organization_name = $category->organization->name;
                }
                $categories_options[$category->id] = $category->name .' ('.$organization_name.')';
            }
            $filter->in('category_id', 'Категория')->select($categories_options);
        });


        if(Admin::user()->isRole('manager')) {
            $grid->model()->where('organization_id', Admin::user()->organization_id);
        }


        $grid->column('id', __('#'));
        $grid->column('organization.name', __('Организация'));
        $grid->column('name', __('Название'));
        $grid->column('category.name', __('Категория'));
        $grid->column('pay_category.name', __('Категория Оплаты'));
        $grid->column('image', __('Изображение'))->image(asset('/'), 200);
       // $grid->column('code', __('Код'));
        $grid->column('weight', __('Вес'));
        $grid->column('price', __('Цена'));
        $grid->column('sort', __('Сортировка'))->editable();
        $grid->column('isIncludedInMenu', __('Отображать в меню'))->switch();
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('organization_id', __('Organization id'));
        $show->field('category_id', __('Category id'));
        $show->field('iiko_id', __('Iiko id'));
        $show->field('isIncludedInMenu', __('IsIncludedInMenu'));
        $show->field('isDeleted', __('IsDeleted'));
        $show->field('code', __('Code'));
        $show->field('price', __('Price'));
        $show->field('parentGroup', __('ParentGroup'));
        $show->field('energyAmount', __('EnergyAmount'));
        $show->field('energyFullAmount', __('EnergyFullAmount'));
        $show->field('fatAmount', __('FatAmount'));
        $show->field('fatFullAmount', __('FatFullAmount'));
        $show->field('fiberAmount', __('FiberAmount'));
        $show->field('fiberFullAmount', __('FiberFullAmount'));
        $show->field('weight', __('Weight'));
        $show->field('image', __('Image'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new Product());

        $form->hasMany('translations', 'Локализация', function (Form\NestedForm $form){
            $form->select('language_id', 'Язык')->options(Language::all()->pluck('name', 'id'))->required();
            $form->text('name', 'Название')->required();
            $form->textarea('description', 'Описание');
        });
        $form->select('organization_id', __('Организация'))->options(Organization::pluck('address', 'id'));

        $categories = Category::get();
        $categories_options = [];
        foreach ($categories as $category){
            $organization_name = 'no organization';
            if($category->organization){
                $organization_name = $category->organization->name;
            }
            $categories_options[$category->id] = $category->name .' ('.$organization_name.')';
        }

        $form->select('category_id', __('Категория'))->options($categories_options);
        $form->text('iiko_id', __('Iiko id'));
        $form->switch('isIncludedInMenu', __('Отображать в меню'));
        $form->switch('isDeleted', __('Удалено'));
        $form->text('code', __('Код'));
        $form->number('price', __('Цена'));
//        $form->text('parentGroup', __('ParentGroup'));
        $form->text('energyAmount', __('Эн. ценность'));
        $form->text('energyFullAmount', __('Эн. ценность (полная)'));
        $form->text('fatAmount', __('Жиры'));
        $form->text('fatFullAmount', __('Жиры (полная)'));
        $form->text('fiberAmount', __('Углеводы'));
        $form->text('fiberFullAmount', __('Углеводы (полная)'));
        $form->text('weight', __('Вес'));
        $form->image('image', __('Изображение'));
        $form->number('sort', __('Сортировка'))->default(0);

        return $form;
    }
}
