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
   
    <form  class="search" method="post" action="{{ route('register') }}">
      @csrf
      <input 
        class="search-input-pc" 
        type="text" 
        placeholder="検索キーワード"
      
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
      
      @if(true == Auth::check())
      <a href="{{ route('article.create')}}" class="link-write-article">
      <button  class="link-write-article-btn">
        投稿する
      </button>
    </a>
      <button class="icon-btn">
        <img src="/storage/profile_image/{{$user->profile_image}}" 
          class="icon-img">
      </button>
      @else
      <a href="{{ route('login') }}" class="link">
        <button 
          class="btn" 
          type="button" 
          onfocus="this.blur(); ">
          ログイン
        </button>
      </a>
     <span></span>
      <a href="{{ route('showregister') }}" class="link">
        <button 
        class="btn" 
        type="button" 
        onfocus="this.blur();">
        新規会員登録</button></a>
      @endif
  </div>
</div>

<nav id="nav"> 
  <ul class=nav-ul>
    <div class="list-box1">

      <li class="nav-list" >
        <a href="{{ route('mypage.profile') }}">マイページへ</a>
    </li>
      <li class="nav-list" >
        <a href="">投稿</a>
      </li>
      <li class="nav-list">
        <a href=""></a>
    </li>
    </div>
  </ul>
</nav>
</header>

<div class="header-around-behind"></div>

<!-- 検索窓 -->
<form  id="search" action="get">
  @csrf
    <input class="search-input" type="text" placeholder="検索キーワード">
    <input class="search-btn1" type="button" onfocus="this.blur(); " value="検索">
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