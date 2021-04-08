@extends('layouts.app_content')

@section('title')
  記事詳細
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/show.css') }}">
</head>
@section('content')
@error('body')
   <div class="comment-error">※{{ $message }} </div>
@enderror
<div class="show-main">
  
  <div class="show-box">
    <div class="show-title-box">
      <div class="show-user-data">
        <img src="/storage/profile_image/{{$article->user->profile_image}}" class="show-user-image">
        <li class="show-article-user">
          {{ $article->user->name}}</li>
          @if(true == Auth::check() && $article->user_id == Auth::user()->id)
          <form action="{{ route('article.status')}}" class="show-form-status" method="get"> 
            @csrf
            @if($article->status === 0)
              <input type="hidden" value="{{ $article->id }}" class="show-status" name="article_id">
              <button type="submit" class="show-status-btn">公開する</button>
            @else
            <input type="hidden" value="{{ $article->id }}" class="show-status" name="article_id">
            <button type="submit" class="show-status-btn">非公開にする</button>
            @endif  
          </form>
          　@endif
        </div>
        <li class="show-title">{{$article->title}}</li>
        <li class="show-created-at">{{ $article->created_at->format('Y年m月d日')}}に投稿</li>
       
        @if(Auth::check() === true && $article->user_id === Auth::user()->id)
        <div class="show-linkbox">
          <a href="{{ action('ArticleController@edit',$article->id)}}" class="show-link-edit">編集</a>

          <form action="{{ route('article.destroy',$article->id)}}"
             method="post" class="destroy-form">
            @csrf
            @method('delete')
            <input type='submit' value="削除" class="show-link-delete" onclick="return confirm('削除しますか？');">
          </form>
        </div>
      @endif
      @if(Auth::check() === true && Auth::id() !== $article->user_id)
        <div class="show-like-box">
        @if($article->is_liked_by_auth_user())
           <img src="{{ asset('/img/good_icon.png')}}" class="show-good-icon">
           <a href="{{ route('unlike',['id' => $article->id])}}" class="show-like">高評価<span class="show-like-count">{{$article->likes->count()}}</span></a>
        @else
           <img src="{{ asset('/img/good_icon.png')}}" class="show-good-icon">
            <a href="{{ route('like',['id' => $article->id])}}" class="show-like">高評価<span class="show-like-count">{{$article->likes->count()}}</span></a>
        @endif
      </div>
      @endif
      </div>
     </div>
      <div class="show-body-box">
        <li class="show-body">{!! $article_parse_body !!}</li>
      </div>
  </div>
  <h2 style=
      "background-color: #eee; 
       color: #333;
       width: 100%;
       height: 40px;
       margin-top: 10px;
       text-align: left;
       font-size: 2.5rem;
       margin-left: 5%;
       ">
       コメント一覧
  </h2>

  <div class="show-border"></div>
  <div class="show-comment-container">
    @foreach($comments as $comment)
    <div class="show-comment-box">
      <li class=comment-user>
        
        <img src="/storage/profile_image/{{$comment->user->profile_image}}"class="comment-user-img">
        <div class="comment-user-name">
          {{ $comment->user->name}}
        </div>
        <div class="comment-created-at">
          {{ $comment->created_at->format('Y年m月d日')}}
        </div>
      </li>
      <li class="comment-body">
        {{ $comment->body}}
      </li>
      </div>
    @endforeach
  </div>

  @if(true == Auth::check())
  <h2 style=
      "background-color: #eee; 
       color: #333;
       width: 100%;
       height: 40px;
       text-align: left;
       font-size: 2.5rem;
       margin-left: 5%;
       margin-top: 60px;
      " 
       id="comment-h2">
       コメントする
  </h2>
  <div class="show-border"></div>
    <form action="{{ route('article.comment')}}">
      @csrf
      <div class="comment-write-box">
        <input type="hidden" name="user_id"
         value="{{Auth::user()->id}}">
         <input type="hidden" name="article_id"
         value="{{$article->id}}">
         <textarea name="body" class="comment-textarea"
         placeholder="コメントを記入してください">
         {{ old('body')}}
        </textarea>
        <button type="submit" class="comment-sent">
          コメント投稿</button>
        </div>
      </form>
  @endif   
  
@endsection