<?php

namespace App\UseCase\Article;

use App\Article;

final class StatusUseCase
{
  public function switchStatus($request)
  {
    $article = Article::find($request->article_id);

       if($article->status === Article::OPEN ) {
            $article->status = Article::CLOSED;
            $article->save();
        }else {
            $article->status = Article::OPEN;
            $article->save();
        }
  }
}