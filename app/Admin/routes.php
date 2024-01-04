<?php

use Illuminate\Routing\Router;

Admin::routes();

app()->setLocale('uk');

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/synchronization', 'SyncController@index');

    $router->get('/clients', 'ClientsController@index')->name('clients');
    $router->get('/clients/{id}', 'ClientsController@show')->name('clients.show');
    $router->get('/clients/{id}/{orderId}', 'ClientsController@order')->name('clients.order');
    $router->get('/synchronization/sync', 'SyncController@sync')->name('synchronization.sync');

    $router->get('/delivery', 'DeliveryController@index');



    $router->get('/dashboard', 'DashboardController@index');
    $router->get('/dashboard/{id}', 'DashboardController@show')->name('dashboard.show');
    $router->post('/dashboard/content', 'DashboardController@content')->name('dashboard.content');
    $router->post('/dashboard/detail', 'DashboardController@detail')->name('dashboard.detail');
    $router->post('/dashboard/archive', 'DashboardController@archive')->name('dashboard.archive');
    $router->post('/dashboard/getArchive', 'DashboardController@getArchive')->name('dashboard.getArchive');
    $router->post('/dashboard/edit', 'DashboardController@edit')->name('dashboard.edit');
    $router->post('/dashboard/update', 'DashboardController@update')->name('dashboard.update');
    $router->post('/dashboard/add_product', 'DashboardController@add_product')->name('dashboard.add_product');
    $router->post('/dashboard/add_product_save', 'DashboardController@add_product_save')->name('dashboard.add_product_save');
    $router->post('/dashboard/submit_order', 'DashboardController@submit_order')->name('dashboard.submit_order');
    $router->post('/dashboard/finish_order', 'DashboardController@finish_order')->name('dashboard.finish_order');
    $router->post('/dashboard/remove_order', 'DashboardController@remove_order')->name('dashboard.remove_order');
    $router->post('/dashboard/give_away_order', 'DashboardController@give_away_order')->name('dashboard.give_away_order');


    $router->resource('auth/users', 'UserController')->names('admin.auth.users');
    $router->resource('ikko-accounts', IikoAccountController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('products', ProductController::class);
    $router->resource('organizations', OrganizationController::class);
    $router->resource('payment-types', PaymentTypeController::class);
    $router->resource('notification', NotificationController::class);
    $router->resource('fops', FopController::class);
    $router->resource('payment-orders', PaymentOrderController::class);

});
