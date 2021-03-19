<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{   
    public function tags() {
        return $this->belongsToMany('App\Tag','article_tags')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
