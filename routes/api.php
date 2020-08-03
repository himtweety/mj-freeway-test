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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$group_routes = function () {
    Route::any('beverages', 'API\Beverages\BeverageController@index');
    Route::any('beverages/check', 'API\Beverages\BeverageController@check');
};
Route::group(['prefix' => 'v1'], $group_routes);
