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

Route::get('/game','App\Http\Controllers\GameController@start')->name('game');
Route::get('/endgame','App\Http\Controllers\GameController@endGame')->name('endgame');
Route::get('/{p_link?}','App\Http\Controllers\HomeController@home')->name('home');

// gamepad
Route::post('/hit','App\Http\Controllers\GameController@hitSquare')->name('hit');
Route::post('/shots-count','App\Http\Controllers\GameController@getShotsCounter')->name('shots-count');
