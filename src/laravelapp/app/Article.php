<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use database\factories\ArticleFactory;
use Illuminate\Support\Facades\Auth;
use App\ArticleTag;

class Article extends Model
{   
    protected $fillable = ['title','body','update_at'];

    const OPEN = 1;
    const CLOSED = 0;

    public function tags() {
        return $this->belongsToMany(
            'App\Tag',
            'article_tags',)
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function parse() :string
    {
      $parser = new \cebe\markdown\Markdown();
      return $parser->parse($this->body);
    }

    public function scopeArticleOpen(object $query) :object
    {
        return $query->where('status',self::OPEN);
    }
    
    public function scopeArticleClosed(object $query) :object
    {
        return $query->where('status',self::CLOSED);
    }

    public function scopeCreated_atDescPaginate(object $query) :object
    {
        return $query->orderBy('created_at','desc')
               ->Paginate(10); 
    }
    public function scopeCreatedAtDescPagenate5(object $query) :object
    {
        return $query->orderBy('created_at', 'desc')
                     ->Paginate(5);
    }
    public function scopeWhereUserId(object $query,$user) :object
    {
        return $query->where('user_id',$user->id);
    }

    public function likes()
    {
        return $this->hasMany(Like::class,'article_id');
    }
    // いいね済みか判定,show.blade.phpで使う
    public function isLikedBy(object $user) :bool 
    {
        return Like::where('user_id',$user->id)
        ->where('article_id',$this->id)
        ->first() !== null;
    }
}
