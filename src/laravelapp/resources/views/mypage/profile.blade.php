@extends('layouts.app_content')

@section('title')
  マイページ
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/profile.css') }}">
</head>
@section('content')
<div class="profile-container">
  <div class="profile-box1">
    <img src="/storage/profile_image/{{ $user->profile_image}}" alt="プロフィールの画像です" class="profile-icon">
    <div class="profile-user-name">
      {{ $user->name }}
    </div>
    <div class="profile-linkbox">
       <a href="{{ url('mypage/profile/myarticle')}}" class="profile-link-menu">
         <div class="profile-article-total">
          {{count($article_count)}}
         </div>
         投稿</a>
       <a href="" class="profile-link-menu">
        <div class="profile-likes-total">
          0
        </div>  
       高評価</a>
       
    </div>
      <button class="profile-link-editprofile">
        <a href="{{ route('mypage.edit-profile')}}" class="profile-link">プロフィール編集</a>
      </button>

  </div>

  <div class="profile-box2">
    <div class="profile-my-article">
    </div>
  <div class="profile-article-box">

    </div>
    @if(isset($article_list))
      @foreach($article_list as $article)
        <li>{{$article}}</li>
      @endforeach

    {{ $article_list->appends(['sort' => $sort])->links() }}
    
    @endif
  </div>
</div>
@endsection

