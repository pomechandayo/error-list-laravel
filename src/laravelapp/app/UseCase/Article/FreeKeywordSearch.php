<?php

namespace App\UseCase\Article;

use App\UseCase\Article\SearchArticleUseCase;
use App\Article;

class FreeKeywordSearch extends SearchArticleUseCase
{
    protected function filtering()
    {
        $free_keyword = $this->free_keyword;
        $article_list = [];

        $article_list = Article::get()->filter(
         function($article) use ($free_keyword) {
                return strpos($article->title,$free_keyword) !== false;
         });
            
        $article_list = Article::get()->filter(
        function($article) use ($free_keyword){
             return strpos($article->body,$free_keyword)
              !== false;
        });

        return $article_list;
    }

    public function getArticleList($free_keyword)
    {
      $this->free_keyword = $free_keyword;
      $article_list = $this->filtering();

      if( $article_list->isEmpty() !== true) {
        foreach($article_list as $article )
        {
           $get_article_list[] = $article->id;
        }
        
        $article_list = Article::with('user','tags','likes','comments')
         ->ArticleOpen()
         ->whereIn('id',$get_article_list)
         ->Created_atDescPaginate();
      }

      return $article_list;
    }
} 