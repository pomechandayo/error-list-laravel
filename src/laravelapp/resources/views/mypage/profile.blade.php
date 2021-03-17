@extends('layouts.app_content')

@section('title')
  マイページ
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/mypage.css') }}">
</head>
@section('content')
<div class="profile-container">
  <div class="profile-box1">
    <img src="/storage/profile_image/{{ $user->profile_image}}" alt="プロフィールの画像です" class="profile-icon">
    <div class="profile-user-name">
      {{ $user->name }}
    </div>
      <button class="profile-link-editprofile">
        <a href="{{ route('mypage.edit-profile')}}" class="profile-link">プロフィール編集</a>
        <button class="profile-link-myarticle">
          <a href="" class="profile-link">投稿記事一覧</a>
        </button>
      </button>

  </div>

  <div class="profile-box2">
    <div class="profile-my-article">
    </div>
  <div class="profile-article-box">

    </div>
  </div>
</div>
@endsection

