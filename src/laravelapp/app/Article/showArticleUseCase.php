<?php

namespace App\Article;

use App\Article;
use App\Comment;

final class ShowArticleUseCase
{
    public function showArticle($id,$user_id)
    {
        $comments = Comment::with(['user',  'replies','replies.user'])->where ('article_id',$id)->get();       
      
        $article = Article::with('User','tags')
        ->find($id);
        $article_parse = new Article;
        $article_parse_body = $article->parse ($article_parse);
    
        return [
             'article' => $article,
             'comments' => $comments,
             'user_id' => $user_id,
             'article_parse_body' => $article_parse_body,
        ];
    }
}