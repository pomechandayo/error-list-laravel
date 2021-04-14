<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class UserPageController extends Controller
{
    public function showUserPage(int $id)
    {
        $sort = 0;
        // ユーザーに紐づいた情報を取得
        $article_list = Article::with('user')
        ->where('user_id',$id)
        ->ArticleOpen()
        ->Created_atDescPaginate();

        foreach($article_list as $article)
        {
            $user = $article->user;
        }
       
        return view('user_page',[
            'article_list' => $article_list,
            'user' => $user,
            'sort' => $sort,
        ]);
    }
}
