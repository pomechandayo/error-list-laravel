<?php

Auth::routes([
    'register' => false
]);


Route::get('index','ArticleController@index')->name('index')->middleware('keyword');

Route::get('article/status','ArticleController@status')
->name('article.status');
Route::get('article/comment','ArticleController@comment')
->middleware('auth')->name('article.comment');

Route::group(['namespace' => 'Auth'],function()
{
    Route::get('/register','RegisterController@showregister')
    ->name('showregister');
    Route::post('/register','RegisterController@register')
    ->name('register');
    Route::get('/logout','LogoutController@getLogout')
    ->name('logout');
    Route::post('/logout','LogoutController@getLogout')
    ->name('post.logout');
});

Route::prefix('mypage')
->namespace('MyPage')
->middleware('auth')
->group(function() 
{
    Route::get('edit-profile','ProfileController@showProfileEditForm')
    ->name('mypage.edit-profile');
    Route::post('edit-profile','ProfileController@editProfile')
    ->name('mypage.edit-profile');
    Route::get('profile','ProfileController@showProfile')
    ->name('mypage.profile');
    Route::get('profile/{menu_link}','ProfileController@showProfile');
});

Route::group(['middleware' => ['auth']],function() 
 {
    Route::resource('article','ArticleController',
    ['only' => ['create','store','edit','update','destroy']]);  
});
Route::resource('article/show','ArticleController',
['only' => ['show']]);

