<?php

namespace App\InterfaceDirectory\Read;

use App\Article;
use App\Comment;
use App\InterfaceDirectory\Read\ReadInterface;
use Illuminate\Support\Facades\Auth;

final class ShowArticleInterface implements ReadInterface
{
    public function read(int $id)
    {
        $comments = Comment::with(['user',  'replies','replies.user'])->where ('article_id',$id)->get();       
      
        $article = Article::with('User','tags')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse ($article_parse);
    
        return [
             'article' => $article,
             'comments' => $comments,
             'user_id' => Auth::id(),
             'article_parse_body' => $article_parse_body,
        ];
    }
}