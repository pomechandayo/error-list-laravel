<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article_tag extends Model
{
    public function article_tags() {
        return $this->belongsTo('App\Tag');
    }
}
