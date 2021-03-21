@extends('layouts.app_content')

@section('titile')
  トップページ
@endsection
<head>
<link 
    rel="stylesheet" 
    href="{{ asset('/css/top.css') }}">
<link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
@section('content')
  <div class="top-main">
    <div class="top-tag-select">

    </div>

    <div class="top-article-container">
      <ul>
        
        @foreach($article_list as $article)
        <div class="top-article_box">
          <li class="top-article-user">
            <img src="/storage/profile_image/{{$article->user->profile_image}}" class="top-article-myimage">{{ $article->user->name}}</li>
          <li class="top-article-title">{{ $article->title}}</li>
          <li class="top-article-tag">
            {{ dd()}}
          {{ $article->tags('name') }}</li>
          <li class="top-article-created_at">{{ $article->created_at}}</li> 
        </div>
          @endforeach
      </ul>
      <div class="top-paginate">
        {{ $article_list->appends(['sort' => $sort])->links() }}
      </div>

    </div>
  </div> 
@endsection
