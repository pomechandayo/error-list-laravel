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
       <h2 class="search-article">
       {{ $tag_keyword }} {{count($article_list)}}件</h2>
       <form action="{{ route('index')}}" method="get" class="tag-form">
       @csrf
      <input 
        class="tag-search" 
        type="text" 
        name="tag_keyword"
        placeholder="タグ名を記入してください"
        vlaue="{{$tag_keyword}}"
      >
      <input 
        class="tag-search-btn" 
        type="image" onfocus="this.blur(); " 
        src="{{ asset('/img/serch2.png')}}" 
        value="検索"
      >
       </form>
     </div>

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
        {{ $article_list->appends(['sort' => $sort])->links() }}
      </div>

    </div>
  </div> 
  <script>
    

  </script>
@endsection
