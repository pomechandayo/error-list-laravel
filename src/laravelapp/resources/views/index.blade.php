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
      @if($articleTotal > 0)
        <h2 class="search-article">
          {{ $keyword }} {{$articleTotal}}件
    　  </h2>
      @else
        <h2 class="search-article">
          {{ $keyword }}
    　  </h2>
      @endif
       <form action="{{ route('index')}}" method="get" class="tag-form">
         @csrf
         <input 
          class="tag-search" 
          type="text" 
          name="keyword"
          placeholder="tag:タグ名  キーワード"
          vlaue="{{$keyword}}"
         >
         <input 
            class="tag-search-btn" 
            type="image" onfocus="this.blur(); " 
            src="{{ asset('/img/serch2.png')}}" 
            value="検索"
          >
       </form>
     </div>
        @if( $article_list != [])
      <ul>
        @foreach($article_list as $article)       
        <div class="top-article_box">
          <li class="top-article-user">
            <img src="/storage/profile_image/{{$article->user->profile_image}}" class="top-article-myimage">{{ $article->user->name}}</li>
                <a href="{{ action('ArticleController@show', $article->id) }}" class="top-link-article">
               <li class="top-article-title">{{ $article->title}}</li>
                </a>
            <li class="top-article-created_at">{{ $article->created_at->format('Y年m月d日')}}に投稿</li>          
          </div>
          @endforeach
      </ul>
      <div class="top-paginate">
          
      </div>
      @endif
      
    </div>
  </div> 
  <script>
    

  </script>
@endsection
