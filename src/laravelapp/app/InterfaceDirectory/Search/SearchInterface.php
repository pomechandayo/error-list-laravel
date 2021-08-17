<?php

namespace App\InterfaceDirectory\Search;

interface SearchInterface
{
    public function search(string $tag_keyword,string $free_keyword );
}