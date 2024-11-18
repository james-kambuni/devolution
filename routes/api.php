<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group( ['middleware' => ['auth:api']], function()
{   
    
    Route::post('/search/user', 'App\Http\Controllers\UserController@search');
    Route::get('/users', 'App\Http\Controllers\UserController@users');
    Route::resource('/user', 'App\Http\Controllers\UserController');
});


Route::post('/login','App\Http\Controllers\AuthController@login');

Route::post('/reset','App\Http\Controllers\AuthController@reset');
Route::post('/reset/save','App\Http\Controllers\AuthController@resetSave');
Route::post('/updatePassword','App\Http\Controllers\AuthController@updatePassword')->middleware('auth:api');
Route::get('/logout','App\Http\Controllers\AuthController@logout');
Route::post('/register','App\Http\Controllers\AuthController@registerUser');
Route::post('/signout/{id}','App\Http\Controllers\VisitController@signout');

Route::post('/register-user','App\Http\Controllers\AuthController@registerUser');