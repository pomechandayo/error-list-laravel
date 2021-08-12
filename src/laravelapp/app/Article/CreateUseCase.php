<?php

namespace App\Article;

use App\User;
use Illuminate\Support\Facades\Auth;

final class CreateUseCase
{
  public function showCreatePage()
  {
    $s3_profile_image = User::GetAuthUserImage();

    return view('article.create',[
        's3_profile_image' => $s3_profile_image,
    ])->with('user',Auth::user());;
  }
}