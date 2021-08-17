<?php

namespace App\InterfaceDirectory\Search;

use App\Article;
use App\Tag;

final class TagAndFreeKeywordSearchInterface implements  SearchInterface
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

        if($article_list !== []){
            $articles = $article_list->filter(
            function($article) use ($free_keyword)
          {
              return strpos($article->title,$free_keyword) !==false;
          });

            $articles = $article_list->filter(
            function($article) use ($free_keyword) {
            return strpos($article->body,$free_keyword) !== false;
            });
        }

        $this->free_keyword = $free_keyword;
      
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