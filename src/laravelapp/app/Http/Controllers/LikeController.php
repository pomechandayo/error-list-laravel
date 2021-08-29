<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Article;
use App\Like;

class LikeController extends Controller
{
    public function likeFirstCheck( $article_id,string $user_id = null) :array
    {
        $count = $this->likeCount($article_id);

        $likes = new Like();
        $like = Like::where('article_id',$article_id)
        ->where('user_id',$user_id)->first();

        if($like !== null ) {
            return [true,$count];
        }
        
        return [false,$count];  
    }

    public function likeCount(string $article_id) :int
    {
        $count = Like::where('article_id',$article_id)
        ->count();

        return $count;
    }

    public function likeCheck(int $article_id, int $user_id) :array
    {

        $like = Like::where('article_id',$article_id)
        ->where('user_id',$user_id)->first();
        
        if($like === null){
            Like::create([
                'article_id' => $article_id,
                'user_id' => $user_id,
            ]);
            $count = $this->likeCount($article_id);
            return [true,$count];
        }else{

            Like::where('article_id',$article_id)
            ->where('user_id',$user_id)->delete();

            $count = $this->likeCount($article_id);
            return [false,$count];
        }

    }

}
