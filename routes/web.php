<?php

use App\Events\NewOrderEvent;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderFinishNotification;
use App\Services\IikoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Nyholm\Psr7\Factory\Psr17Factory;
use OneSignal\Config;
use OneSignal\OneSignal;
use Symfony\Component\HttpClient\Psr18Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/sync_ph3m4klfsldf', 'SyncController@sync')->name('iiko_sync');

Route::any('/webhook/api_transport/get', 'WebhookController@get')->name('webhook.api_transport.get');
Route::any('/webhook/fondy', 'PaymentController@webhook')->name('webhook.fondy');


Route::get('/', 'MainController@index')->name('main');
Route::post('/set_geo', 'MainController@set_geo')->name('set_geo');
Route::get('/privacypolicy', 'MainController@privacypolicy')->name('privacypolicy');
Route::get('/main/set_pickup_place/{id}', 'MainController@set_pickup_place')->name('main.set_pickup_place');
Route::get('/language/{language}', 'LanguageController@set')->name('language.set');
Route::get('/events', 'EventController@index')->name('events');


Route::name('favorite.')->prefix('favorite')->group(function () {
    Route::get('/favorite', 'FavoriteController@index')->name('index');
    Route::post('/favorite/store', 'FavoriteController@store')->name('store');
});
Route::name('dashboard.')->prefix('dashboard')->group(function () {
    Route::get('/', 'AccountController@index')->name('index');
    Route::post('/rename', 'AccountController@rename')->name('rename');
    Route::post('/remove', 'AccountController@remove')->name('remove');
});

Route::name('menu.')->prefix('menu')->group(function () {
    Route::get('/', 'MenuController@index')->name('index');
    Route::get('/{id}', 'MenuController@show')->name('show');
});


Route::any('/order/pay-status', 'OrderController@payStatus')->name('pay-status');

Route::middleware('auth')->name('order.')->prefix('order')->group(function () {
    Route::get('/', 'OrderController@index')->name('index');
    Route::get('/fondy/{id}', 'OrderController@fondy')->name('fondy');
    Route::post('/add_to_cart', 'OrderController@add_to_cart')->name('add_to_cart');
    Route::post('/store', 'OrderController@store')->name('store');
    Route::post('/cancellation', 'OrderController@cancellation')->name('cancellation');
    Route::post('/basket', 'OrderController@basket')->name('basket');
    Route::post('/update', 'OrderController@update')->name('update');
    Route::post('/comment', 'OrderController@comment')->name('comment');;
});

Route::name('password.')->prefix('password')->group(function () {
    Route::post('/send_sms', 'Auth\PasswordResetViaSmsController@sendSms')->name('send_sms');
    Route::get('/reset_via_sms', 'Auth\PasswordResetViaSmsController@reset_via_sms')->name('reset_via_sms');
    Route::post('/update_via_sms', 'Auth\PasswordResetViaSmsController@update')->name('update_via_sms');
});

Route::name('register.')->prefix('register')->group(function () {
    Route::post('/send_sms', 'Auth\RegisterController@sendSms')->name('send_sms');
    Route::get('/confirm/{id}', 'Auth\RegisterController@confirm')->name('confirm');
    Route::post('/confirm/{id}', 'Auth\RegisterController@confirmDone')->name('confirmDone');
});

Route::post('save-token-os', 'IosTokenController@store')->name('save-token-os');





