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

Route::get('/','FrontController@index');

Route::post('/cart-add','CartController@add')->name('cart.add');

Route::get('/cart-checkout','CartController@cart')->name('cart.checkout');

Route::post('/cart-clear','CartController@clear')->name('cart.clear');

Route::post('/cart-removeitem','CartController@removeItem')->name('cart.removeitem');


//Route::resource('dashboard/product', 'dashboard\ProductController');

