<?php

namespace App\InterfaceDirectory\Search;

use App\Article;
use App\Tag;

final class TagSearchInterface implements  SearchInterface
{
    public function search(string $tag_keyword,string $free_keyword )
    {
        $article_list = [];
        $tag_number = 
            Tag::where('name',$tag_keyword)
            ->first('id');

        if($tag_number !== null){

          $article_list = Tag::find($tag_number->id)
          ->articles->sortByDesc('created_at');
        }

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