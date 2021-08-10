<?php

namespace App\Article;

use App\Article;

final class newArticleShowUseCase
{
  public function newArticle10()
  {
    $article_list = Article::with('user','tags','likes','comments')
    ->ArticleOpen()
    ->Created_atDescPaginate();
    $keyword = '新着記事一覧';
   
    return [
         'article_list' => $article_list,
         'search_keyword' => $keyword,
    ];
  }
}