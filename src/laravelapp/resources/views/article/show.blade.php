@extends('layouts.app_content')

@section('title')
  記事詳細
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/show.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('content')
@error('body')
   <div class="comment-error">※{{ $message }} </div>
@enderror
<div class="show-main">
  
  <div class="show-box">
    <div class="show-user-data">
    <a href="{{ route('userpage.show',[$article->user->id])}}"><img src="{{ $user_image.$article->user->profile_image ?? asset ('/img/default_image.png')}}" class="show-user-image"></a>
      <li class="show-article-user">
        {{ $article->user->name}}</li>
        <li class="show-created-at">{{ $article->created_at->format('Y年m月d日')}}</li>
    </div>     
        <div class="show-title-box">
          <li class="show-title">{{$article->title}}</li>
        </div>

        <div class="show-tag">
            @foreach($article->tags as $tag)
            #{{$tag->name}}
            @endforeach             
        </div>

        <div class="show-like-box">
          @auth
              <!-- Review.phpに作ったisLikedByメソッドをここで使用 -->
              @if (!$article->isLikedBy(Auth::user()))
                <span class="likes">
                    <i class="like-toggle" data-article-id="{{ $article->id }}">高評価</i>
                      <span class="like-counter">{{ $article->likes_count ?? $article->likes->count()}}
                      </span>
                      </span>
              @else
                <span class="likes">
                    <i class="like-toggle liked" data-article-id="{{ $article->id }}">高評価</i>
                    <span class="like-counter">{{$article->likes_count ?? $article->likes->count()}}
                    </span>
                    </span>
              @endif
            @endauth
            @guest
              <span class="likes">
                  <i class="like-toggle-guest">高評価</i>
                <span class="like-counter">
                  {{$article->likes->count()}}</span>
              </span>
            @endguest

           <!-- 記事を書いたユーザーであれば公開、非公開を切り替えるリンクを表示 -->
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

       <!-- 記事を書いたユーザーであれば編集と削除のリンクを表示 -->
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
        </div>
        <!-- ここまでlike-box -->
      </div>
      <!-- ここまでshowーbox -->

      <div class="show-body-box">
        <li class="show-body">{!! $article_parse_body !!}</li>
      </div>

  </div>
  <!-- ここまでshow-main -->

  <!-- ここからコメント -->
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
      <a href="{{ route('userpage.show',[$comment->user->id])}}"><img src="{{ $user_image.$comment->user->profile_image ?? asset ('/img/default_image.png')}}" class="comment-user-img"></a>
        <div class="comment-user-name">
          {{ $comment->user->name}}
        </div>
        <div class="comment-created-at">
          {{ $comment->created_at->format('Y年m月d日')}}
        </div>
        @if(Auth::id() === $comment->user_id)
          <a href="{{ route('article.comment.delete',['id' => $comment->id]) }}" class="comment-delete" onclick="return confirm('削除しますか？')">削除</a>
        @endif
      </li>
      <li class="comment-body">
        {{ $comment->body}}
      </li>
     
      <!-- ここからリプライ -->
      @foreach($comment->replies as $reply)
      <div class="reply-box">
        <div class="reply-user-data">
        <a href="{{ route('userpage.show',[$reply->user->id])}}"><img src="/storage/profile_image/{{$reply->user->profile_image}}" class="reply-img"></a>
          {{$reply->user->name}}
          {{$reply->created_at->format('Y年m月d日')}}
        @if(Auth::id() === $reply->user_id)
            <a href="{{ route('article.reply.delete',['id' => $reply->id])}}" class="reply-delete"onclick="return confirm('削除しますか？')">削除</a>
        @endif
        </div>
        
          <li class="replies">
            {{$reply->body}}
          </li>
      </div>
      @endforeach

      @if(!empty($comment->id) && Auth::check() === true)
      <form action="{{route('article.reply')}}" class="reply-form"
      method="get">
        @csrf
        <div class="reply-sent-box">
          <input type="hidden" name="user_id"
          value="{{Auth::user()->id}}">
          <input type="hidden" name="comment_id"
          value="{{$comment->id}}">
          <input type="hidden" name="article_id"
          value="{{$article->id}}">
          <textarea name="body" class="reply-textarea"
          placeholder="コメントを記入してください">
          {{ old('body')}}
          </textarea>
          <button type="submit" class="reply-sent">
            返信する</button>
          </div>
      </form>
     @endif
    </div>
    @endforeach
  </div>

  @if(Auth::check() === true)
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

  <ul id="error_message"></ul>
  <div class="show-border"></div>
    <form action="{{ route('article.comment')}}" method="post">
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
        <button type="submit" id="comment-sent" class="comment-sent">
          コメント投稿</button>
        </div>
    </form>

  @endif   

 
@endsection