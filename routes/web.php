<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'CustomerController@index')->name('customer');

Route::get('invoice', 'InvoiceController@index')->name('invoice');
Route::get('customer', 'CustomerController@index')->name('customer');

Route::prefix('delivery-lead')->group(function () {
    Route::get('', 'DeliveryLeadController@index')->name('delivery-lead');
    Route::get('print', 'DeliveryLeadController@print')->name('delivery-lead-print');
});

