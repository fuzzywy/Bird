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
Auth::routes();

Route::get('localeLang', 'NavController@localeLang');

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/cog', 'CogController@index')->name('cog');


Route::get('getTabs', 'NetworkOverviewController@getTabs');
Route::get('getScaleTabs', 'NetworkOverviewController@getScaleTabs');
Route::get('getLoadTabs', 'NetworkOverviewController@getLoadTabs');
Route::get('getcharts', 'NetworkOverviewController@getcharts');


Route::get('test', 'TestController@test');

Route::get('getCity', 'NetworkOverviewController@getCity');
Route::get('getBirdSideBar', 'NetworkOverviewController@getBirdSideBar');

Route::post('uploadCog', 'UploadController@uploadCog');
Route::post('showCog', 'UploadController@showCog');
Route::post('deleteCog', 'UploadController@deleteCog');
/*Route::get('/test', function() {
	return view('test.test', ['name'=>'James']);
});

Route::get('example', function () {
    return view('example');
});

Route::get('foo', 'TestController@test');

Route::get('indexoverview', 'IndexOverviewController@index');
Route::get('scaleoverview', 'ScaleOverviewController@scale');

// Route::get('getTabs', 'NetWorkOverviewController@getTabs');

Route::get('getTabsLTE', 'NetWorkOverviewLTEController@getTabs');
Route::get('getTabsVOLTE', 'NetWorkOverviewVOLTEController@getTabs');

Route::get('getchartsLTE', 'NetWorkChartsLTEController@getcharts');
Route::get('getchartsVOLTE', 'NetWorkChartsVOLTEController@getcharts');



//----*/
