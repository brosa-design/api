<?php

use Illuminate\Http\Request;

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

Route::resource('products', 'ProductsController');
Route::get('products/{id}/variants', 'ProductsController@getVariants');
Route::post('variants/{id}/attributes/{attribute?}', 'VariantsController@store');
Route::post('order/make', 'OrderController@store');
