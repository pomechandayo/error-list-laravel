<?php

namespace App\InterfaceDirectory\Search;

use App\Article;

final class LatestArticleInterface implements SearchInterface
{
    public function search(string $tag_keyword,string $free_keyword )
    {
        $article_list = Article::with('user','tags','likes','comments')
        ->ArticleOpen()
        ->Created_atDescPaginate();
      
        return $article_list;
    }
}