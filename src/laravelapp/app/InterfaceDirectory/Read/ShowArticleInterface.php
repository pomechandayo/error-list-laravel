<?php

namespace App\InterfaceDirectory\Read;

use App\Article;

final class ShowArticleInterface implements ReadInterface
{
    public function read(object $reqest)
    {
        $article_list = Article::with('user','tags','likes','comments')
        ->ArticleOpen()
        ->Created_atDescPaginate();
      
        return $article_list;
    }
}