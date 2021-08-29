<?php

namespace App\InterfaceDirectory\Read;

use App\User;
use Illuminate\Support\Facades\Auth;

final class ShowCreateInterface implements ReadInterface
{
    public function read(int $id)
    {
      $s3_profile_image = User::GetAuthUserImage();

      return view('article.create',[
        's3_profile_image' => $s3_profile_image,
    ])->with('user',Auth::user());
    }
}