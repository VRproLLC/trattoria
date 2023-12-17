<?php

use App\Events\NewOrderEvent;
use App\Notifications\InProgressNotification;
use App\Notifications\OrderFinishNotification;
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



Route::get('/test', function (){

    echo (base64_decode('eyJvcmRlciI6IHsicnJuIjogIjA0MjY2ODk5MTk4MCIsICJtYXNrZWRfY2FyZCI6ICI1MzYzNTRYWFhYWFg0MTYzIiwgInNlbmRlcl9jZWxsX3Bob25lIjogIiIsICJzZW5kZXJfYWNjb3VudCI6ICIiLCAiY3VycmVuY3kiOiAiVUFIIiwgImZlZSI6ICIiLCAicmV2ZXJzYWxfYW1vdW50IjogIjAiLCAic2V0dGxlbWVudF9hbW91bnQiOiAiMCIsICJhY3R1YWxfYW1vdW50IjogIjUwIiwgInJlc3BvbnNlX2Rlc2NyaXB0aW9uIjogIiIsICJzZW5kZXJfZW1haWwiOiAic3Rhcy56YmFyYXpza2lpQGdtYWlsLmNvbSIsICJvcmRlcl9zdGF0dXMiOiAiYXBwcm92ZWQiLCAicmVzcG9uc2Vfc3RhdHVzIjogInN1Y2Nlc3MiLCAib3JkZXJfdGltZSI6ICIxNC4xMi4yMDIzIDAxOjIyOjM1IiwgImFjdHVhbF9jdXJyZW5jeSI6ICJVQUgiLCAib3JkZXJfaWQiOiAiMTUzNzM1MV81N2ZmYTYzNDFlOThmYWUyMmNmZWM3MmY0MjY0OGI3ZSIsICJ0cmFuX3R5cGUiOiAicHVyY2hhc2UiLCAiZWNpIjogIjUiLCAic2V0dGxlbWVudF9kYXRlIjogIiIsICJwYXltZW50X3N5c3RlbSI6ICJjYXJkIiwgImFwcHJvdmFsX2NvZGUiOiAiMzE2MDQ5IiwgIm1lcmNoYW50X2lkIjogMTUzNzM1MSwgInNldHRsZW1lbnRfY3VycmVuY3kiOiAiIiwgInBheW1lbnRfaWQiOiA3MDM3NDI5NDQsICJjYXJkX2JpbiI6IDUzNjM1NCwgInJlc3BvbnNlX2NvZGUiOiAiIiwgImNhcmRfdHlwZSI6ICJNYXN0ZXJDYXJkIiwgImFtb3VudCI6ICI1MCIsICJwcm9kdWN0X2lkIjogImVlNmJjZTFmLWM1ZTQtNGVmMy1hNWE5LWU2OWRjODE5MjVmZCIsICJtZXJjaGFudF9kYXRhIjogIntcIm9yZGVyX2lkXCI6XCJlZTZiY2UxZi1jNWU0LTRlZjMtYTVhOS1lNjlkYzgxOTI1ZmRcIn0iLCAicmVjdG9rZW4iOiAiIiwgInJlY3Rva2VuX2xpZmV0aW1lIjogIiIsICJ2ZXJpZmljYXRpb25fc3RhdHVzIjogIiIsICJwYXJlbnRfb3JkZXJfaWQiOiAiIiwgImFkZGl0aW9uYWxfaW5mbyI6ICJ7XCJjYXB0dXJlX3N0YXR1c1wiOiBudWxsLCBcImNhcHR1cmVfYW1vdW50XCI6IG51bGwsIFwicmVzZXJ2YXRpb25fZGF0YVwiOiBcInt9XCIsIFwidHJhbnNhY3Rpb25faWRcIjogMTgzOTM4MjI3MywgXCJiYW5rX3Jlc3BvbnNlX2NvZGVcIjogbnVsbCwgXCJiYW5rX3Jlc3BvbnNlX2Rlc2NyaXB0aW9uXCI6IG51bGwsIFwiY2xpZW50X2ZlZVwiOiAwLjAsIFwic2V0dGxlbWVudF9mZWVcIjogMC4wMSwgXCJiYW5rX25hbWVcIjogXCJQUklWQVRCQU5LXCIsIFwiYmFua19jb3VudHJ5XCI6IFwiVUFcIiwgXCJjYXJkX3R5cGVcIjogXCJNQVNURVJDQVJEXCIsIFwiY2FyZF9wcm9kdWN0XCI6IFwiZW1wdHlfbWNcIiwgXCJjYXJkX2NhdGVnb3J5XCI6IFwiV09STEQgQkxBQ0tcIiwgXCJ0aW1lZW5kXCI6IFwiMTQuMTIuMjAyMyAwMToyMzoxNVwiLCBcImlwYWRkcmVzc192NFwiOiBcIjE0OS4xMDIuMTU1Ljk3XCIsIFwicGF5bWVudF9tZXRob2RcIjogXCJjYXJkXCIsIFwidmVyc2lvbl8zZHNcIjogMiwgXCJmbG93XCI6IFwiY2hhbGxlbmdlXCJ9In19'));

});

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

Route::name('order.')->prefix('order')->group(function () {
    Route::get('/', 'OrderController@index')->name('index');
    Route::any('/pay-status', 'OrderController@payStatus')->name('pay-status');
    Route::get('/fondy/{id}', 'OrderController@fondy')->name('fondy');
    Route::post('/add_to_cart', 'OrderController@add_to_cart')->name('add_to_cart');
    Route::post('/store', 'OrderController@store')->name('store');
    Route::post('/cancellation', 'OrderController@cancellation')->name('cancellation');
    Route::post('/basket', 'OrderController@basket')->name('basket');
    Route::post('/update', 'OrderController@update')->name('update');
    Route::post('/comment', 'OrderController@comment')->name('comment');


    Route::get('/update_status_from_iiko', 'OrderController@update_status_from_iiko')->name('update_status_from_iiko');
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





