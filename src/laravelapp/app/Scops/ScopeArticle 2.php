<?php
namespace App\Scops;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ScopeArticle implements Scope
{
  public function apply(Builder $builder,Model $model)
  {
    
  }
}