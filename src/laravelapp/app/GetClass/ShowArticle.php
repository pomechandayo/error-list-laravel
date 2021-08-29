<?php

namespace App\GetClass;

use App\InterfaceDirectory\Read\ShowArticleInterface;
use App\InterfaceDirectory\Read\ShowCreateInterface;
use App\InterfaceDirectory\Read\ShowEditInterface;

final class ShowArticle
{
  private $id;
  private $selectClassWord;
  
  public function __construct(int $id,$selectClassWord)
  {
      $this->id = $id;
      $this->selectClassWord = $selectClassWord;
  }
  
  public function showArticleClass()
  {
    switch($this->selectClassWord){
    case 'show':
        $interface = new ShowArticleInterface;
        return $interface->read($this->id,  $this->selectClassWord);
        break;
    case 'create':
        $interface = new ShowCreateInterface;
        return $interface->read($this->id,  $this->selectClassWord);
        break;
    case 'edit':
        $interface = new ShowEditInterface;
        return $interface->read($this->id,  $this->selectClassWord);
        break;
    
    }
  }
}