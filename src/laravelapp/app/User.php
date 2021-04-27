<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class User extends Model implements Authenticatable
{
    use AuthenticableTrait;

    protected $fillable = ['name','email','password'];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function scopeGetS3Url() :string
    {
        $user = User::where('id',1)->first();
        $path = Storage::disk('s3')->url($user->profile_image);

        $path = str_replace($user->profile_image,'',$path);
    
        return $path;
    }

    public function scopeGetAuthUserImage() :string
    {
        $s3_url = 0;
        
        if(Auth::id() !== null){

        $user = User::where('id',Auth::id())
        ->first();
        $s3_url = $this->scopeGetS3Url().$user->profile_image;
        }
        return $s3_url;
    }

}
