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

    /*ページネーション、orederByができる*/
    public function getArticleList($num_per_page = 10) {
        return $this->orderBy('id','desc')
        ->paginate($num_per_page);
    }
}
