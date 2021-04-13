<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticableTrait;

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function likes()
    {
        return $this->hasMany('App\Like');
    }
}
