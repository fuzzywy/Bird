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
Route::prefix('birdCards')->namespace('BirdCards')->group(function() {
  Route::post('show', 'BirdCardsController@show');
});
Route::prefix('birdChart')->namespace('BirdChart')->group(function() {
  Route::post('show', 'BirdChartController@show');
});
// Route::get('/test/{province}/{city?}', 'BirdRegion\BirdRegionController@test');
Route::prefix('birdCog')->namespace('birdCog')->group(function() {
  Route::post('show', 'BirdCogController@show');
  Route::post('edit', 'BirdCogController@edit');
  Route::post('delete', 'BirdCogController@delete');
});

Route::prefix('barChart')->namespace('BarChart')->group(function() {
  Route::post('show', 'BarChartController@show');
});
Route::prefix('bubbleChart')->namespace('BubbleChart')->group(function() {
  Route::post('show', 'BubbleChartController@show');
});
Route::prefix('pieChart')->namespace('PieChart')->group(function() {
  Route::post('show', 'PieChartController@show');
});
Route::prefix('topCell')->namespace('TopCell')->group(function() {
  Route::post('show', 'TopCellController@show');
});