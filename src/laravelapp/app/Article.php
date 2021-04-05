<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use database\factories\ArticleFactory;
use App\ArticleTag;

class Article extends Model
{   
    protected $fillable = ['title','body','update_at'];

    public function tags() {
        return $this->belongsToMany(
            'App\Tag',
            'article_tags',
            'tag_id',
            'article_id',
            )->withTimestamps();
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
