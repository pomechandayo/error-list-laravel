<?php

namespace App\UseCase\Article;

abstract class SearchArticleUseCase
{
    protected $tag_keyword = "";
    protected $free_keyword = "";

    abstract protected function filtering();

    abstract public function getArticleList($keyword); 
} 