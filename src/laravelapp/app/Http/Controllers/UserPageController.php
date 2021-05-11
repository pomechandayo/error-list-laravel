<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Article;
use App\User;

class UserPageController extends Controller
{
    public function showUserPage(int $user_id)
    {
        $article_count = Article::where('user_id',$user_id)->count();
        
        $article_list = Article::with('user','tags','likes','comments')
        ->where('user_id',$user_id)
        ->ArticleOpen()
        ->Created_atDescPaginate();

        foreach($article_list as $article){
            
            $user_data = $article->user;
        }
       
        return [
            'article_list' => $article_list,
            'article_count'=> $article_count,
            'user_data' => $user_data,
        ];
    }
}
