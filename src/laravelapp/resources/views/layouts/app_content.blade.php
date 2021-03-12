<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, , initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <link rel="stylesheet" href="{{ asset('/css/style.css')}}" >

  <title>@yield('title') | {{config('app.name','Laravel')}}</title>
 
</head>
<body>
<header>
<div class="header-box">
  <div class="header-left">
    <a href="{{ route('top')}}" class="logo">Errors</a>
  </div>
  <div class="header-right">
    
    <form  class="search" action="get">
    @csrf
      <input class="search-input-pc" type="text" placeholder="検索キーワード">
      <input class="search-btn-pc" type="image" onfocus="this.blur(); " src="{{ asset('/img/serch1.png')}}" value="検索">
  </form>
      <button class="search-btn" type="button" onfocus="this.blur(); "><img src="{{ asset('/img/serch1.png')}}" class="serch-img"></button>
      <a href="" class="link"><button class="btn" type="button" onfocus="this.blur(); ">ログイン</button></a>
     <span></span>
      <a href="{{ route('showregister') }}" class="link"><button class="btn" type="button" onfocus="this.blur();">新規会員登録</button></a>
  </div>
</div>
<nav id="nav"> 
  <ul class=nav-ul>
    <div class="list-box1">
      <li class="nav-list" ><a href="">投稿</a></li>
      <li class="nav-list"><a href=""></a></li>
    </div>
  </ul>
</nav>
</header>

<div class="header-around-behind"></div>

<form  id="search" action="get">
  @csrf
    <input class="search-input" type="text" placeholder="検索キーワード">
    <input class="search-btn1" type="button" onfocus="this.blur(); " value="検索">
</form>

@yield('content')

<footer>
<p>Errors</p>
  <small>
    &copy;Eroors incremnts Inc. 2021
  </small> 
</footer>

<script>
    function selector(select){
      return document.querySelector(select);
    }
    
    selectSearch = selector('#search');
    selectNav = selector('#nav');

    selector('.search-btn').onclick = function () {
      if(selectSearch.id == 'search'){
        selectSearch.setAttribute('id','search1');
      }else {
        selectSearch.setAttribute('id','search');
      }
    }

    selector('.btn').onclick = function () {
      if(selectNav.id == 'nav') {
        selectNav.setAttribute('id','nav1');
      }else {
        slectNav.setAttribute('id','nav');
      }
    }

  </script>
</body>
</html>