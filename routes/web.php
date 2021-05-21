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
    return view('layouts.default', ['title' => 'Welcome']);
});

Route::get('register', 'App\Http\Controllers\RegistrationController@showForm')->name('register');
Route::post('register', 'App\Http\Controllers\RegistrationController@process');

Route::get('login', "App\Http\Controllers\LoginController@showForm")->name('login');
Route::post('login', "App\Http\Controllers\LoginController@login");
Route::get('logout', "\App\Http\Controllers\LoginController@logout");

Route::get('contacts', "\App\Http\Controllers\ContactsController@all")->name('contacts');
Route::get('/contact/{id?}', "\App\Http\Controllers\ContactController@one");
Route::post('/contact/{id?}', "\App\Http\Controllers\ContactController@createOrUpdate");
Route::delete('/contact/{id}', "\App\Http\Controllers\ContactController@delete");

Route::get('shares', "\App\Http\Controllers\SharesController@all")->name('shares');

Route::get('/share/{contact_id}', "\App\Http\Controllers\ShareController@listUsers");
Route::post('/share/{contact_id}/{user_id}', "\App\Http\Controllers\ShareController@shareContact");
Route::delete('/share/{contact_id}/{user_id}', "\App\Http\Controllers\ShareController@unshareContact");
