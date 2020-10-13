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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/transport', function () {
    return view('transport')
        ->with('pins', \App\Pin::all())
        ->with("buses", \App\Bus::all())
        ->with("drivers", \App\Driver::all())
        ->with("pass_bies", \App\PassBy::all())
        ->with("lines", \App\Line::all());
})->middleware('auth')->name('transport');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('pins/reset', 'PinController@reset');
Route::resource('pins', 'PinController');
Route::get('paths/reset', 'PathController@reset');
Route::resource('paths', 'PathController');

Route::resource('buses', 'BusController');
Route::resource('drivers', 'DriverController');
Route::resource('lines', 'LineController');
Route::resource('pass_bies', 'PassByController');
