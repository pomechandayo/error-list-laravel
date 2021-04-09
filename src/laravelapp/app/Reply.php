<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{   
    protected $fillable = ['comment_id','user_id','body'];

    public function comments()
    {
        return $this->belongsTo(Comment::class);
    } 
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
