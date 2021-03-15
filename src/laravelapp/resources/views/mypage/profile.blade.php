@extends('layouts.app_content')

@section('title')
  マイページ
@endsection

@section('content')
<div class="profile-container">
  <div class="profile-box1">
    <img src="/storage/profile_image/{{ $user->profile_image}}" alt="プロフィールの画像です" class="profile-icon">
    <div class="profile-username">
      {{ $user->name }}
      <a href="">投稿</a>
      <button>
        <a href="{{ route('mypage.edit-profile')}}">プロフィール編集</a>
      </button>
    </div>

  </div>

  <div class="profile-box2">
    <div class="profile-article-box">
      
    </div>
  </div>
</div>
@endsection

