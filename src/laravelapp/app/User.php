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
    {   $path = '';
        $user = User::where('id',1)->first();

        if($user->profile_image !== null){
        $path = Storage::disk('s3')->url($user->profile_image);
        }
        
        $path = str_replace($user->profile_image,'',$path);

        return $path;
    }

    public function scopeGetAuthUserImage() 
    {
        $s3_url = "";
        
        if(Auth::id() !== null){

        $user = User::where('id',Auth::id())
        ->first();
        $s3_url = $user->profile_image;
        }
        if($s3_url === null){
            $s3_url = "dd";
        }
        return $s3_url;
    }

}
