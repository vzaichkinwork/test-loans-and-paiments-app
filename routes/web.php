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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('calc')->name('calc.')->group(function () {
    Route::get('/', 'CalcController@getView')->name('form');
    Route::post('conversion', 'CalcController@getConversion')->name('conversion');
});

Route::prefix('import')->name('import.')->group(function () {
    Route::get('loans', 'ImportController@getLoansView')->name('loans.form');
    Route::get('payments', 'ImportController@getPaymentsView')->name('payments.form');

    Route::post('loans', 'ImportController@storeLoans')->name('loans.store');
    Route::post('payments', 'ImportController@storePayments')->name('payments.store');
});

Route::get('loans', 'TableController@getTable')->name('loans.index');
