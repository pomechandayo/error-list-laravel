<?php

namespace App\Article;

use App\Article;

class TagAndFreeKeywordSearch
{
    protected $free_keyword = "";

    protected function filtering()
    {
      $free_keyword = $this->free_keyword;

      if($this->article_list !== []){
      $articles = $this->article_list->filter(
        function($article) use ($free_keyword)
            {
                return strpos($article->title,$free_keyword) !==false;
            });

      $articles = $this->article_list->filter(
      function($article) use ($free_keyword) {
         return strpos($article->body,$free_keyword) !== false;
      });
    }
    return $articles;
  }

    public function getArticleList(string $free_keyword,$article_list)
    {
        $this->free_keyword = $free_keyword;
        $this->article_list = $article_list;
        $articles = $this->filtering();
        
        if( $articles->isEmpty() !== true) {
          foreach($articles as $article )
          {
             $get_article_list[] = $article->id;
          }
          /*記事情報と紐付けられたユーザー情報取得*/
          $article_list = Article::with('user','tags','likes','comments')
          ->ArticleOpen()
          ->whereIn  ('id',$get_article_list)
          ->Created_atDescPaginate();
        }else{
          return $article_list = [];
        }

        return $article_list;

    }
} 