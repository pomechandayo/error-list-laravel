<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['article_id','user_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
