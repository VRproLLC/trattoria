<?php

use App\Events\NewOrderEvent;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderFinishNotification;
use Carbon\Carbon;
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

Route::get('/test', function (){
    return response('Developed the code Zbara Dev');
})->name('test');

Route::get('/', 'MainController@index')->name('main');
Route::post('/set_geo', 'MainController@set_geo')->name('set_geo');
Route::get('/privacypolicy', 'MainController@privacypolicy')->name('privacypolicy');
Route::get('/main/set_pickup_place/{id}', 'MainController@set_pickup_place')->name('main.set_pickup_place');

Route::get('/events', 'EventController@index')->name('events');

Route::get('/favorite', 'FavoriteController@index')->name('favorite.index');
Route::post('/favorite/store', 'FavoriteController@store')->name('favorite.store');

Route::get('/dashboard', 'AccountController@index')->name('dashboard.index');
Route::post('/dashboard/rename', 'AccountController@rename')->name('dashboard.rename');
Route::post('/dashboard/remove', 'AccountController@remove')->name('dashboard.remove');

Route::get('/language/{language}', 'LanguageController@set')->name('language.set');

Route::get('/menu', 'MenuController@index')->name('menu.index');
Route::get('/menu/{id}', 'MenuController@show')->name('menu.show');

Route::get('/order', 'OrderController@index')->name('order.index');
Route::post('/order/add_to_cart', 'OrderController@add_to_cart')->name('order.add_to_cart');
Route::post('/order/store', 'OrderController@store')->name('order.store');
Route::post('/order/cancellation', 'OrderController@cancellation')->name('order.cancellation');
Route::post('/order/basket', 'OrderController@basket')->name('order.basket');
Route::post('/order/update', 'OrderController@update')->name('order.update');
Route::post('/order/comment', 'OrderController@comment')->name('order.comment');
Route::get('/order/update_status_from_iiko', 'OrderController@update_status_from_iiko')->name('order.update_status_from_iiko');

Route::post('/password/send_sms', 'Auth\PasswordResetViaSmsController@sendSms')->name('password.send_sms');
Route::get('/password/reset_via_sms', 'Auth\PasswordResetViaSmsController@reset_via_sms')->name('password.reset_via_sms');
Route::post('/password/update_via_sms', 'Auth\PasswordResetViaSmsController@update')->name('password.update_via_sms');

Route::any('/webhook/api_transport/get', 'WebhookController@get')->name('webhook.api_transport.get');
Route::post('save-token-os', 'IosTokenController@store')->name('save-token-os');


Route::post('/register/send_sms', 'Auth\RegisterController@sendSms')->name('register.send_sms');
Route::get('/register/confirm/{id}', 'Auth\RegisterController@confirm')->name('register.confirm');
Route::post('/register/confirm/{id}', 'Auth\RegisterController@confirmDone')->name('register.confirmDone');



