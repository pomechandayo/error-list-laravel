<?php

namespace App\Article;

use App\Article;

class newArticleShowUseCase
{
  public function newArticle10()
  {
    $article_list = Article::with('user','tags','likes','comments')
    ->ArticleOpen()
    ->Created_atDescPaginate();

    return $article_list;
  }
}