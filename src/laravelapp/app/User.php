<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function articles()
    {
        return $this->hasMany('App\Article');
    }
}
