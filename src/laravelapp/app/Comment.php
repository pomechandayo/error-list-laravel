<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   protected $fillable = ['user_id','article_id','body'];

   public function user()
   {
       return $this->belongsTo(User::class,'user_id','id')
       ->select('id','name','profile_image','created_at');
   }
   public function replies()
   {
       return $this->hasMany(Reply::class);
   }
   public function articles()
   {
       return $this->belogsTo(Article::class);
   }
}
