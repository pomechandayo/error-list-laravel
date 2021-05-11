<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Article;
use App\Like;

class LikeController extends Controller
{
    public function likeFirstCheck(int $article_id, $user_id = null) 
    {
        
       
        $likes = new Like();
        $like = Like::where('article_id',$article_id)
        ->where('user_id',$user_id)->first();

        if($like !== null ) {
            $count = $likes->where('article_id',$article_id)
            ->count();

            return [true,$count];
        }else{
            $count = $likes->where('article_id',$article_id)
            ->count();
            
            return [false,$count];
        }
    }

    public function likeCheck(int $article_id, int $user_id) 
    {
        $likes = new Like();
        $like = Like::where('article_id',$article_id)
        ->where('user_id',$user_id)->first();
        
        if($like === null){
            Like::create([
                'article_id' => $article_id,
                'user_id' => $user_id,
            ]);
            $count = $likes->where('article_id',$article_id)
            ->count();

            return [true,$count];
        }else{

            Like::where('article_id',$article_id)
            ->where('user_id',$user_id)->delete();

            $count = $likes->where('article_id',$article_id)
            ->count();

            return [false,$count];
        }
    }
    public function like(Request $request)
    {
        $user_id = Auth::id(); 
        $article_id = $request->article_id;
        
        $already_liked = Like::where('user_id', $user_id)->where('article_id', $article_id)->first();   
        if (!$already_liked) {   
            Like::create([
                'article_id' => $article_id,
                'user_id' => $user_id 
            ]);
        } else { 
            Like::where('article_id', $article_id)->where('user_id', $user_id)->delete();
        }
     
    }

}
