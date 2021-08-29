<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{ 
    const OPEN = 1;
    const CLOSED = 0;
    protected $fillable = ['name'];

    public function articles() {
    return $this->belongsToMany(
        'App\Article',
        'article_tags',
    );
    }

    public function scopeArticleOpen(object $query) :object
    {
        return $query->where('status',self::OPEN);
    }
    
    public function scopeCreated_atDescPaginate(object $query) :object
    {
        return $query->orderBy('created_at','desc')
               ->Paginate(10); 
    }
}
