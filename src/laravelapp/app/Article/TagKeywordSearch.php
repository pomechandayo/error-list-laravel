<?php

namespace App\Article;

use App\Article\SearchArticleUseCase;
use App\Article;
use App\Tag;

class TagKeywordSearch extends SearchArticleUseCase
{
    protected function filtering()
    { 
        $article_list = [];
        $tag_number = 
            Tag::where('name',$this->tag_keyword)
            ->first('id');

        if($tag_number !== null){

          $article_list = Tag::find($tag_number->id)
          ->articles->sortByDesc('created_at');
        }
        
        return $article_list;
    }

    public function getArticleList($tag_keyword)
    {
      $this->tag_keyword = $tag_keyword;
      $article_list = $this->filtering();

      if( $article_list !== []) {
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