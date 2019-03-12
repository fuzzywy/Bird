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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('birdSideBar')->namespace('BirdSideBar')->group(function() {
  Route::post('show', 'BirdSideBarController@show');
});
Route::prefix('birdOperators')->namespace('BirdOperators')->group(function() {
  Route::post('show', 'BirdOperatorsController@show');
});
Route::prefix('birdRegion')->namespace('BirdRegion')->group(function() {
  Route::post('show', 'BirdRegionController@show');
});
Route::prefix('birdTypes')->namespace('BirdTypes')->group(function() {
  Route::post('show', 'BirdTypesController@show');
});
Route::prefix('birdChart')->namespace('BirdChart')->group(function() {
  Route::post('show', 'BirdChartController@show');
});
