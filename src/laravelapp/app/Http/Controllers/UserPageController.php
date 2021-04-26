<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Article;
use App\User;

class UserPageController extends Controller
{
    public function showUserPage(int $id)
    {
        $user = Auth::user();
        $user_image = User::GetS3Url();
        $s3_profile_image = User::GetAuthUserImage();
        
        $article_list = Article::with('user','tags','likes','comments')
        ->where('user_id',$id)
        ->ArticleOpen()
        ->Created_atDescPaginate();

        foreach($article_list as $article){
            
            $user_data = $article->user;
        }
       
        return view('user_page',[
            's3_profile_image' => $s3_profile_image,
            'user_image' => $user_image,
            'article_list' => $article_list,
            'user_data' => $user_data,
            'user' => $user,
        ]);
    }
}
