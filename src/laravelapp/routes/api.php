<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api']],function() {
    Route::post('/index','ArticleController@index')
    ->middleware('keyword');

    Route::get('/article/show/{id}','ArticleController@show');

    Route::get('/profile/{id}','MyPage\ProfileController@getProfileImage');
    Route::get('/mypage/show/{user_id}/{keyword?}','Mypage\ProfileController@showProfile');

    Route::get('/userpage/{user_id}','UserPageController@showUserPage');

    Route::get('/like/{article_id}/{user_id}/likeFirstCheck','LikeController@likeFirstCheck');
    Route::get('/like/{article_id}/{user_id}/likeCheck','LikeController@likeCheck');

    Route::get('/article/{article_id}/edit','ArticleController@edit');


});

