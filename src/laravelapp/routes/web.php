<?php

Auth::routes([
    'register' => false
]);


// Route::get('index','ArticleController@index')->name('index')->middleware('keyword');

Route::get('/article/status','ArticleController@status')
->name('article.status');

Route::get('/register',function() {
    return view('auth.register');
})->name('register');
Route::get('/login',function() {
    return view('auth.register');
})->name('login');
Route::get('/index',function(){
    return view('/index');
})->name('index');
Route::get('/article/show',function() {
    return view('/article/show');
})->name('article.show');

Route::get('/mypage/show',function(){
    return view('/mypage/show');
})->name('mypage.show')->middleware('auth');

Route::get('/mypage/edit',function(){
    return view('/mypage/edit');
})->name('mypage.edit')->middleware('auth');
Route::post('/mypage/edit','Mypage\ProfileController@editProfile')
->name('mypage.edit.post')->middleware('auth');



Route::group(['namespace' => 'Auth'],function(){

    Route::post('/register','RegisterController@register')
    ->name('register');
    Route::get('/logout','LogoutController@getLogout')
    ->name('logout');
});

Route::prefix('/article')
->middleware('auth')
->name('article.')
->group(function()
{
    Route::post('/comment','ArticleController@comment')
    ->name('/comment');
    Route::get('/comment/delete','ArticleController@commentDelete')
    ->name('comment.delete');

    Route::get('/reply','ArticleController@reply')
    ->name('reply');
    Route::get('/reply/delete','ArticleController@replyDelete')
    ->name('reply.delete');
});


Route::prefix('/mypage')
->namespace('MyPage')
->middleware('auth')
->group(function()
{
    Route::get('/profile/edit','ProfileController@showProfileEditForm')
    ->name('mypage.edit-profile');
 
    Route::get('/profile','ProfileController@showProfile')
    ->name('mypage.profile');
    Route::get('/profile/{menu_link}','ProfileController@showProfile');
});

Route::group(['middleware' => ['auth']],function() 
 {
    Route::resource('article','ArticleController',
    ['only' => ['create','store','edit','update','destroy']]);  
});



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

Route::post('/destroy','ArticleController@destroy');

Route::post('/api/profile','MyPage\ProfileController@getProfileImage');
