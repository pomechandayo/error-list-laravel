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
        <a href="{{ url('mypage/profile/myarticle_all')}}" class="profile-link-menu">
        <div class="profile-article-total">
          {{count($article_count)}}
        </div>
        投稿した記事</a>
        <a href="{{ url('mypage/profile/my_comment_article')}}" class="profile-link-menu">
            <div class="profile-likes-total">
            {{$comment_count->count()}}
            </div>  
            コメントした記事
          </a>
       
        
          
        </div>
        <button class="profile-link-editprofile">
          <a href="{{ route('mypage.edit-profile')}}" class="profile-link">プロフィール編集</a>
        </button>
      </div>
      </div>
      
      <!-- ここから記事一覧 -->
      <div class="profile-container2">
      @if( !empty($article_list))
        <div class="profile-fillter-box">
          <a href="{{ url('mypage/profile/myarticle_all')}}" class="profile-fillter-link">全て</a>
          <a href="{{ url('mypage/profile/myarticle_open')}}" 
          class="profile-fillter-link">公開</a>
          <a href="{{ url('mypage/profile/myarticle_closed')}}" 
          class="profile-fillter-link">非公開</a>

        </div>

        @foreach($article_list as $article)
          <div class="profile-article-box">
             <li class="profile-article-user">
             <img src="/storage/profile_image/{{$article->user->profile_image}}" class="profile-myimage"> 
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
                  {{$article->created_at->format('Y年m月d日')}}に投稿
                  <div class="count_box">
                    <span class="mypage-like-count" style="margin-left: auto;">高評価{{$article->likes->count()}}
                    </span>
                      <span class="mypage-comment-count">コメント数
                        {{$article->comments->count()}}
                      </span>
                  </div>
            </li>
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
      
      