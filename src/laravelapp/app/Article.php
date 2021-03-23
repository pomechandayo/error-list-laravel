<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Article extends Model
{   
    public function tags() {
        return $this->belongsToMany('App\Tag','article_tags')->withTimestamps();
    }

    public function article_tags() {
        return $this->belongsTo('App\Article_tag');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

  public function parse() {
      $parser = new \cebe\markdown\Markdown();
      return $parser->parse($this->body);
  }
}
