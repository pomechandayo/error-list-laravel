<?php

namespace App\InterfaceDirectory\Read;

use App\Article;
use Illuminate\Support\Facades\Auth;

final class ShowEditInterface implements ReadInterface
{
    public function read(int $article_id)
    {
      $tag_list = Article::find($article_id)->tags->pluck('name');
      $tags = $tag_list->toArray();
      $tags_string = implode(" #",$tags);

      $article_data = Article::find($article_id);
  
      $article_parse = new Article;
      $article_parse_body = $article_data->parse  ($article_parse);

      return [
         $article_parse_body,
         $article_data,
         $tags_string,
      ];
    }
}