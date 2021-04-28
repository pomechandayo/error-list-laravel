<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, , initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" href="{{ asset('/css/style.css')}}" >
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

  <title>@yield('title') | {{config('app.name','Laravel')}}</title>
 
</head>
<body>
<header>
<div class="header-box">
  <div class="header-left">
    <a href="{{ route('index')}}" class="logo">ErrorList</a>
  </div>
  <div class="header-right">
   
    <form  class="search" method="get" action="{{ route('index')}}">
      @csrf
      <input 
        class="search-input-pc" 
        type="text" 
        placeholder="tag:タグ名 キーワード"
        name="keyword"
      >
      <input 
        class="search-btn-pc" 
        type="image" onfocus="this.blur(); " 
        src="{{ asset('/img/serch1.png')}}" 
        value="検索"
      >
  </form>
 
      <button 
        class="search-btn" 
        type="button" 
        onfocus="this.blur(); "
      >
          <img src="{{ asset('/img/serch1.png')}}" class="search-img">
      </button>
        <!-- ログインしていればユーザーのアイコンが表示される -->
      @if(true == Auth::check())
      <a href="{{ route('article.create')}}" class="link-write-article">
      <button  class="link-write-article-btn">
        投稿する
      </button>
    </a>
        <img src="{{ $s3_profile_image ?? asset ('/img/default_image.png')}}" 
          class="icon-img" style="margin: 0 10px;">
      <!-- ログインしていない場合、ログインと新規会員登録のリンクが表示される -->
      @else
      <a href="{{ route('login') }}" class="link">
        <button 
          id="btn" 
          type="button" 
          onfocus="this.blur();"
           >
          ログイン
        </button>
      </a>
     <span style="margin: 0 0 0 0;"></span>
      <a href="{{ route('showregister') }}" class="link">
        <button 
        id="btn" 
        type="button" 
        onfocus="this.blur();"
        >
        会員登録</button></a>
      @endif
  </div>
</div>
@if(true == Auth::check())
<nav id="nav"> 
  <ul class="nav-ul">
      <li class="nav-list" >
        <a href="{{ route('mypage.profile') }}" class="nav-link">
          マイページ
        </a>
    </li>
      <li class="nav-list" >
        <a href="{{ route('article.create')}}" class="nav-link">投稿</a>
      </li>
      <li class="nav-list">
        <form action="{{ route('logout') }}"
        method="POST">
        @csrf
        <a 
        href=
        "{{ route('logout') }}" class="nav-link">
        ログアウト
      </a>
      </form>
    </li>
    </div>
  </ul>
</nav>
@endif
</header>

<div class="header-around-behind"></div>

<!-- 検索窓 -->
<form  id="search" action="{{route('index')}}" method="get">
  @csrf
    <input class="search-input" type="text" placeholder="検索キーワード" name="keyword">
    <input class="search-btn1" type="image" onfocus="this.blur(); " 
    src="{{ asset('/img/serch2.png')}} " 
    style="
    height: 25px;
    width: 25px;
    background-color: #fff;
    border: 0 none;
    margin-left: 5px;
    ">
</form>

@yield('content')

<footer>
<div class="footer-logo" >ErrorList</div>
  <small>
    &copy;Eroors incremnts Inc. 2021
  </small> 
</footer>

<script type="text/javascript" src="{{ asset('/js/main.js') }}"></script>
</body>
</html>