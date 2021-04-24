@extends('layouts.app_content')

@section('title')
  マイページ
@endsection
<head>
<link rel="stylesheet" href="{{ asset('/css/profile.css') }}">
<link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
      .profile-container1{ min-height: 330px; height: auto;}
      .profile-article-total{ font-size: 1.7rem; margin-top: 5px;}
      .profile-link-menu {
        display: inline-block;
        color: #444;
        height: 30px;
        font-size: 1.8rem;
        margin-bottom: 20px;
      }
      
      .profile-link-menu:hover{
        text-decoration: none;
        color: #444;
      }
    </style>
</head>
@section('content')
<div class="profile-container-lg">
  <div class="profile-container1">
    <div class="profile-box1">
   <img src="/storage/profile_image/{{ $user_data->profile_image}}" class="profile-icon">
      <div class="profile-user-name">
        {{ $user_data->name }}
      </div>
      <div class="profile-linkbox">
        <div href="" class="profile-link-menu">
        <div class="profile-article-total">
          {{$article_list->count()}}
        </div>
        投稿した記事</div>        
          
        </div>
      </div>
      </div>
      
      <!-- ここから記事一覧 -->
      <div class="profile-container2">
      @if( !empty($article_list))
       
          @foreach($article_list as $article)
            <div class="profile-article-box">
              <li class="profile-article-user">
              <a href="{{ route('userpage.show',[$user_data->id])}}"><img src="/storage/profile_image/{{$article->user->profile_image}}" class="profile-myimage"></a>
              {{$article->user->name}}
              <div class="mypage_article_tag">
                @foreach($article->tags as $tag)
                  #{{$tag->name}}
                @endforeach
              </div>
              </li>
              
              <a href="{{ action('ArticleController@show', $article->id) }}" class="profile-link-article">
              <li class="profile-article-title">{{$article->title}}</li>
              </a>
              <li class="profile-article-created_at">
                    {{$article->created_at->format('Y年m月d日')}}
                    <div class="count_box">
                      <span class="mypage-like-count" style="margin-left: auto;">高評価{{$article->likes->count()}}
                      </span>
                      <span class="mypage-comment-count">コメント数{{$article->comments->count()}}
                      </span>
                    </div>
              </li>
          </div>
        @endforeach
        <div class="profile-paginate">
          {{ $article_list->links() }}
        </div>
        @endif
        <!-- ここまで記事一覧 -->
        </div>
      </div>
    </div>
      @endsection
      
      
      