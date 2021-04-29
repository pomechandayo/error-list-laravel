<?php

Auth::routes([
    'register' => false
]);


Route::get('index','ArticleController@index')->name('index')->middleware('keyword');

Route::get('article/status','ArticleController@status')
->name('article.status');
Route::post('/like','ArticleController@like')
->name('like');

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

Route::prefix('article')
->middleware('auth')
->name('article.')
->group(function()
{
    Route::post('comment','ArticleController@comment')
    ->name('comment');
    Route::get('comment/delete/{id}','ArticleController@comment_delete')
    ->name('comment.delete');

    Route::get('reply','ArticleController@reply')
    ->name('reply');
    Route::get('reply/delete/{id}','ArticleController@reply_delete')
    ->name('reply.delete');
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

Route::get('article/show/{id}','ArticleController@show')->name('article.show');

Route::get('userpage/show/{id}','UserPageController@showUserPage')
->name('userpage.show');

Route::prefix('login/google')
->namespace('Auth')
->name('login.google')
->group(function(){

    Route::get('','LoginController@redirectToGoogle')
    ->name('');
    Route::get('callback','LoginController@handleGoogleCallback')
    ->name('.callback');
});

Route::view('index1','index1');