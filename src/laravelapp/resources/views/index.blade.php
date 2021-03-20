@extends('layouts.app_content')

@section('titile')
  トップページ
@endsection
<head>
<link 
    rel="stylesheet" 
    href="{{ asset('/css/top.css') }}">
</head>
@section('content')
  <div class="top-main">
    <div class="top-tag-select">

    </div>

    <div class="top-article-container">
      <ul>
        
        @foreach($article_list as $article)
          <li class="top-article-user->">{{ $article->user->name}}</li>
          <li class="top-article-title">{{ $article->title}}</li>
          <li class="top-article-created_at">{{ $article->created_at}}</li>
    
          @endforeach
      </ul>
      <div class="top-paginate">
        {{ $article_list->appends(['sort' => $sort])->links() }}
      </div>

    </div>
  </div> 
@endsection
