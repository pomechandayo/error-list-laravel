@extends('layouts.app_content')

@section('title')
  マイページ
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/profile.css') }}">
<link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
@section('content')
<div class="profile-container-lg">
  <div class="profile-container1">
    <div class="profile-box1">
      <img src="/storage/profile_image/{{ $user->profile_image}}" class="profile-icon">
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
      </div>
      
      <!-- ここから記事一覧 -->
      <div class="profile-container2">
      @if(isset($article_list))
      @foreach($article_list as $article)
      <div class="profile-article-box">
        <li class="profile-article-user">
          <img src="/storage/profile_image/{{$article->user->profile_image}}" class="profile-myimage">  
          {{$article->user->name}}</li>
          <a href="{{ action('ArticleController@show', $article->id) }}" class="profile-link-article">
            <li class="profile-article-title">{{$article->title}}</li>
          </a>
          <li class="profile-article-created_at">{{$article->created_at->format('Y年m月d日')}}に投稿</li>
        </div>
        @endforeach
        <div class="profile-paginate">
          {{ $article_list->appends(['sort' => $sort])->links() }}
        </div>
        @endif
        <!-- ここまで記事一覧 -->
        </div>
      </div>
    </div>
      @endsection
      
      