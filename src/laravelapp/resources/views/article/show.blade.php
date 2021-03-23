@extends('layouts.app_content')

@section('title')
  記事詳細
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/show.css') }}">
</head>
@section('content')
  <div class="show-main">
    <div class="show-title-box">
      <div class="show-user-data">
        <img src="/storage/profile_image/{{$article->user->profile_image}}" class="show-user-image">
        <li class="show-article-user">
        {{ $article->user->name}}</li>
      </div>
        <li class="show-title">{{$article->title}}</li>
        <li class="show-created-at">{{ $article->created_at->format('Y年m月d日')}}に投稿</li>
      @if($article->user_id == $article->user->id)
        <div class="show-linkbox">
          <a href="" class="show-link-edit">編集</a>
          <a href="" class="show-link-delete">削除</a>
        </div>
      @endif
     </div>
      <div class="show-body-box">
        <li class="show-body">{!! $article_parse_body !!}</li>
      </div>
  </div>
@endsection