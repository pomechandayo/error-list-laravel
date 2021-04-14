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

<div class="top-article-container">
 
    <div class="search-result">
     @if(!empty($article_list))
      <h2 class="search-article">
        {{  $search_keyword ?? $keyword }}
        {{$article_list->total()}}件
  　  </h2>
     @else
      <h2 class="search-article">
        {{  $search_keyword ?? $keyword }}
  　  </h2>
     @endif
       <form action="{{ route('index')}}" method="get" class="tag-form">
         @csrf
         <input 
          class="tag-search" 
          type="text" 
          name="keyword"
          placeholder="tag:タグ名  キーワード"
          value="{{ $keywords ?? '' }}"
         >
         <input 
            class="tag-search-btn" 
            type="image" onfocus="this.blur(); " 
            src="{{ asset('/img/serch2.png')}}" 
            value="検索"
          >
       </form>
     </div>
        @if( $article_list !== [])
      <ul>
        @foreach($article_list as $article)       
        <div class="top-article_box">
          <li class="top-article-user">
            <a href="{{ route('userpage.show',[$article->user->id])}}"><img src="/storage/profile_image/{{$article->user->profile_image}}" class="top-article-myimage"></a>
            {{ $article->user->name}}
            <div class="top-tag">
                  @foreach($article->tags as $tag)
                   #{{$tag->name}}
                  @endforeach             
            </div>
          </li>
                <a href="{{route('article.show',[$article->id])}}" class="top-link-article">
                  <li class="top-article-title">{{ $article->title}}</li>
                </a>
                <li class="top-article-created_at">{{      $article->created_at->format('Y年m月d日')}}に投稿
                  <span class="top-like-count" style="margin-left: auto;">高評価{{$article->likes->count()}}
                </span>
                  <span class="top-like-count">コメント数
                    {{$article->comments->count()}}
                  </span>
                </li>
          </div>
          @endforeach
         
      </ul>
      <div class="top-paginate">
          
      </div>
      @endif
      @if(!empty($article_list))
          {{ $article_list->links() }}
      @endif
    </div>
  </div> 
  <script>
    

  </script>
@endsection
