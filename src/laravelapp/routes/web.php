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

Auth::routes([
    'register' => false
]);

Route::get('/home', 'HomeController@index')->name('top');

Route::get('/register','Auth\RegisterController@showregister')->name('showregister');
Route::post('/register','Auth\RegisterController@register')->name('register');
Route::prefix('mypage')
->namespace('MyPage')
->middleware('auth')
->group(function() {
    Route::get('edit-profile','ProfileController@showProfileEditForm')
    ->name('mypage.edit-profile');
});