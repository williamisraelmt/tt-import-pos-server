<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::domain('admin.' . env('HOST'))->group(function () {

        Route::prefix('entities')->group(function () {

            Route::get('invoice', 'InvoiceController@index')->name('invoice');

            Route::get('customer', 'CustomerController@index')->name('customer');

            Route::get('payment', 'PaymentController@index')->name('payment');

            Route::get('user', 'UserController@index')->name('user');

            Route::get('product', 'ProductController@index')->name('product');

            Route::get('product-brand', 'ProductBrandController@index')->name('product-brand');

            Route::get('product-category', 'ProductCategoryController@index')->name('product-category');

            Route::get('product-department', 'ProductDepartmentController@index')->name('product-department');

            Route::get('product-model', 'ProductModelController@index')->name('product-model');

            Route::get('product-type', 'ProductTypeController@index')->name('product-type');

            Route::get('debt-collector', 'DebtCollectorController@index')->name('debt-collector');

            Route::prefix('delivery-lead')->group(function () {

                Route::get('', 'DeliveryLeadController@index')->name('delivery-lead');

                Route::get('print', 'DeliveryLeadController@print')->name('delivery-lead-print');

            });

        });

        Route::prefix('tools')->group(function () {

            Route::get('commission-calculator', 'CommissionCalculatorController@index')->name('commission-calculator');

        });

        Route::redirect('/', '/entities/customer');

    });

});


Auth::routes([
    'register' => false,
    'verify' => false,
    'confirm' => false,
    'reset' => false,
    'logout' => false
]);

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('settings', 'SettingsController@index')->name('catalog');

});

Route::get('/', 'CatalogController@index')->name('catalog');

Route::redirect('/#/{vue_route}', '/');

