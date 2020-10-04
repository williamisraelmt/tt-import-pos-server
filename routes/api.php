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

Route::post('sync', 'SyncController@sync');

Route::prefix('delivery-lead')->group(function () {
    Route::get('', 'DeliveryLeadController@showAll');
    Route::post('', 'DeliveryLeadController@create');
    Route::delete('{id}', 'DeliveryLeadController@delete');
});

Route::prefix('invoice')->group(function () {
    Route::get('', 'InvoiceController@showAll');
//    Route::get('customer/{customer_id}', 'InvoiceController@showUsingCustomer');
});
Route::prefix('customer')->group(function () {
    Route::get('list', 'CustomerController@showList');
    Route::get('', 'CustomerController@showAll');
    Route::get('invoices/{customer_id}', 'InvoiceController@showByCustomer');
});
