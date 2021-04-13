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


    public function parse() {
      $parser = new \cebe\markdown\Markdown();
      return $parser->parse($this->body);
    }

    public function scopeArticleOpen($query)
    {
        return $query->where('status',self::OPEN);
    }
    
    public function scopeArticleClosed($query)
    {
        return $query->where('status',self::CLOSED);
    }

    public function scopeCreated_atDescPaginate($query)
    {
        return $query->orderBy('created_at','desc')
               ->Paginate(10); 
    }

    public function likes()
    {
        return $this->hasMany(Like::class,'article_id');
    }
    public function isLikedBy($user) :bool 
    {
        return Like::where('user_id',$user->id)
        ->where('article_id',$this->id)
        ->first() !== null;
    }
}
