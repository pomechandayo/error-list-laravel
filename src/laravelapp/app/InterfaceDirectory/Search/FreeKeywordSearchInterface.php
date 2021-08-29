<?php

namespace App\InterfaceDirectory\Search;

use App\Article;

final class FreeKeywordSearchInterface implements  SearchInterface
{
    public function search(string $tag_keyword,string $free_keyword )
    {
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