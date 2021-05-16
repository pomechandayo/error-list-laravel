<?php

Auth::routes([
    'register' => false
]);


Route::get('/register',function() {
    return view('auth.register');
})->name('register');
Route::get('/login',function() {
    return view('auth.register');
})->name('login');

Route::get('/index',function(){
    return view('/index');
})->name('index');

 Route::prefix('/article')
 ->name('article.')
 ->group(function () {

    Route::get('/show',function() {
        return view('/article/show');
    })->name('show');

    Route::middleware('auth')
    ->group(function () {

        Route::get('/{article_id}/edit',function() {
            return view('/article/edit');
        })->name('edit');
        Route::post('/update','ArticleController@update')
        ->name('updata');
        
        Route::get('/status','ArticleController@status')
        ->name('status');
        Route::post('/destroy','ArticleController@destroy')
        ->name('destroy');

        Route::post('/comment','ArticleController@comment')
        ->name('/comment');
        Route::get('/comment/delete','ArticleController@commentDelete')
        ->name('comment.delete');

        Route::get('/reply','ArticleController@reply')
        ->name('reply');
        Route::get('/reply/delete','ArticleController@replyDelete')
        ->name('reply.delete');
        });
});

Route::prefix('/mypage')
->middleware('auth')
->name('mypage.')
->group(function() {

    Route::get('/show',function(){
        return view('/mypage/show');
    })->name('show');
    Route::get('/edit',function(){
        return view('/mypage/edit');
    })->name('edit');
    Route::post('/edit','Mypage\ProfileController@editProfile')
    ->name('post');
    
});

Route::group(['namespace' => 'Auth'],function(){

    Route::post('/register','RegisterController@register')
    ->name('register');
    Route::get('/logout','LogoutController@getLogout')
    ->name('logout');
});

Route::group(['middleware' => ['auth']],function() 
 {
    Route::resource('article','ArticleController',
    ['only' => ['create','store']]); 
});

Route::prefix('login/google')
->namespace('Auth')
->name('login.google')
->group(function(){

    Route::get('','LoginController@redirectToGoogle')
    ->name('');
    Route::get('callback','LoginController@handleGoogleCallback')
    ->name('.callback');
});

