<?php

namespace App\UseCase\Article;

use App\Article;

final class NewArticleShowUseCase
{
  public function newArticle10()
  {
    $article_list = Article::with('user','tags','likes','comments')
    ->ArticleOpen()
    ->Created_atDescPaginate();

    return $article_list;
  }
}