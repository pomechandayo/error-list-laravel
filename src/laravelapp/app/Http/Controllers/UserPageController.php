<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Article;

class UserPageController extends Controller
{
    public function showUserPage(int $id)
    {
        $user = Auth::user();
        // ユーザーに紐づいた情報を取得
        $article_list = Article::with('user','tags','likes','comments')
        ->where('user_id',$id)
        ->ArticleOpen()
        ->Created_atDescPaginate();

        foreach($article_list as $article)
        {
            $user_data = $article->user;
        }
       
        return view('user_page',[
            'article_list' => $article_list,
            'user_data' => $user_data,
            'user' => $user,
        ]);
    }
}
