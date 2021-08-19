<?php

namespace App\UseCase\Article;

final class SearchWordUseCase
{
    public function SearchKeywordSelect($tag_keyword,$free_keyword,$article_list)
    {
        if( empty($article_list)){
          $search_keyword = $tag_keyword." ". $free_keyword .'に一致する検索結果はありませんでした';
        }else {
            $search_keyword = $tag_keyword." ". $free_keyword .'の検索結果';
        }

        if( empty($tag_keyword) && empty($free_keyword)){

            $search_keyword = '新着記事';
        }

        return $search_keyword;

    }
}