<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth', 'role:customer|admin'])->group(function(){

    Route::prefix('user')->group(function () {
        Route::get('logged', 'UserController@loggedUser');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    });

});

Route::prefix('product')->group(function () {
    Route::get('', 'ProductController@showCatalog');
    Route::get('photo/{productId}/{path}', 'ProductController@showPhoto');
});

Route::middleware('role:admin')->group(function(){

    Route::domain('admin.tt-import-pos-server.test')->group(function () {

        Route::prefix('sync')->group(function () {
            Route::post('', 'SyncController@sync');
            Route::get('latest', 'SyncController@latest');
        });

        Route::prefix('delivery-lead')->group(function () {
            Route::get('', 'DeliveryLeadController@showAll');
            Route::post('', 'DeliveryLeadController@create');
            Route::delete('{id}', 'DeliveryLeadController@delete');
        });

        Route::prefix('user')->group(function () {
            Route::get('/logged', 'UserController@logged');
            Route::get('list', 'UserController@showList');
            Route::get('{id}', 'UserController@show');
            Route::get('', 'UserController@showAll');
            Route::put('', 'UserController@store');
            Route::delete('{id}', 'UserController@delete');
        });

        Route::prefix('debt-collector')->group(function () {
            Route::get('', 'DebtCollectorController@showAll');
            Route::get('list', 'DebtCollectorController@showList');
            Route::get('{id}', 'DebtCollectorController@show');
            Route::put('', 'DebtCollectorController@store');
        });

        Route::prefix('invoice')->group(function () {
            Route::get('', 'InvoiceController@showAll');
        });

        Route::prefix('payment')->group(function () {
            Route::get('', 'PaymentController@showAll');
            Route::put('debt-collector/{paymentId}', 'PaymentController@updateDebtCollector');
        });


        Route::prefix('product')->group(function () {
            Route::put('{productId}/favorite-photo/{photoId}', 'ProductController@markAsFavoritePhoto');
            Route::get('photo/{productId}/{path}', 'ProductController@showPhoto');
            Route::put('status/{productId}', 'ProductController@updateStatus');
            Route::get('photos/{productId}', 'ProductController@showPhotos');
            Route::post('photos/{productId}', 'ProductController@uploadPhoto');
            Route::delete('photos/{productId}/{photoId}', 'ProductController@deletePhoto');
            Route::get('', 'ProductController@showAll');
        });

        Route::prefix('customer')->group(function () {
            Route::get('list', 'CustomerController@showList');
            Route::get('', 'CustomerController@showAll');
            Route::get('invoices/{customerId}', 'CustomerController@showInvoicesWithCustomer');
            Route::put('debt-collector/{customerId}', 'CustomerController@updateDebtCollector');
        });

        Route::prefix('commission-calculator')->group(function () {
            Route::post('debt-collector-payment', 'CommissionCalculatorController@getPaymentsByDebtCollectors');
        });

        Route::prefix('role')->group(function () {
            Route::get('list', 'RoleController@showList');
        });

    });

});
