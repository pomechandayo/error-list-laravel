<?php

namespace App;

use App\InterfaceDirectory\Search\LatestArticleInterface;
use App\InterfaceDirectory\Search\SearchInterface;
use App\InterfaceDirectory\Search\TagSearchInterface;
use App\InterfaceDirectory\Search\FreeKeywordSearchInterface;
use App\InterfaceDirectory\Search\TagAndFreeKeywordSearchInterface;

final class GetClass
{
  private $free_keyword;
  private $tag_keyword;
  
  public function __construct(string $tag_keyword,string $free_keyword)
  {
      $this->free_keyword = $free_keyword;
      $this->tag_keyword = $tag_keyword;
  }
  
  public function searchClass()
  {
    if(empty($this->tag_keyword) && empty($this->free_keyword)) {

        $interface = new LatestArticleInterface();

        return $interface->search
        ($this->tag_keyword,$this->free_keyword);

    } elseif(!empty($this->tag_keyword) && !empty($this->free_keyword)) {

        $interface = new TagAndFreeKeywordSearchInterface;

        return $interface->search
        ($this->tag_keyword,$this->free_keyword);

    } elseif(!empty($this->tag_keyword) && empty($this->free_keyword)) {

        $interface = new TagSearchInterface;

        return $interface->search
        ($this->tag_keyword,$this->free_keyword);

    }elseif(empty($this->tag_keyword) && !empty($this->free_keyword)) {

        $interface = new FreeKeywordSearchInterface;

        return $interface->search
        ($this->tag_keyword,$this->free_keyword);

    }

  }
}