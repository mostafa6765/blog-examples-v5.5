<?php

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

Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::route('dashboard');
    }

    return Redirect::route('app.home');
});

Route::get('/home', [
    'name' => 'Home',
    'as' => 'app.home',
    'uses' => 'HomeController@home',
]);

Route::group(['middleware' => ['authorize', 'auth']], function () {
    Route::get('/dashboard', [
        'name' => 'Dashboard',
        'as' => 'dashboard',
        'uses' => 'HomeController@dashboard',
    ]);
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/authorize/{token}', [
        'name' => 'Authorize Login',
        'as' => 'authorize.device',
        'uses' => 'Auth\AuthorizeController@verify',
    ]);

    Route::post('/authorize/resend', [
        'name' => 'Authorize',
        'as' => 'authorize.resend',
        'uses' => 'Auth\AuthorizeController@resend',
    ]);
});

Route::get('/calculator', [
    'name' => 'Loan Calculator',
    'as' => 'loan.calculator',
    'uses' => 'CalculatorController@calculator',
]);

Route::get('/paypal/{order?}', [
    'name' => 'PayPal Express Checkout',
    'as' => 'order.paypal',
    'uses' => 'PayPalController@form',
]);

Route::post('/checkout/payment/{order}/paypal', [
    'name' => 'PayPal Express Checkout',
    'as' => 'checkout.payment.paypal',
    'uses' => 'PayPalController@checkout',
]);

Route::get('/paypal/checkout/{order}/completed', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.completed',
    'uses' => 'PayPalController@completed',
]);

Route::get('/paypal/checkout/{order}/cancelled', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.cancelled',
    'uses' => 'PayPalController@cancelled',
]);

Route::post('/webhook/paypal/{order?}/{env?}', [
    'name' => 'PayPal Express IPN',
    'as' => 'webhook.paypal.ipn',
    'uses' => 'PayPalController@webhook',
]);

Route::group(['prefix' => 'blog'], function () {
    Route::get('/', [
        'as' => 'app.blog',
        'uses' => 'BlogController@index',
    ]);
    Route::get('/{slug}', [
        'as' => 'app.blog.view',
        'uses' => 'BlogController@view',
    ]);
});
