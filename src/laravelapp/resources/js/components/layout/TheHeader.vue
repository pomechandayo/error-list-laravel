<template>
  <header>
<div class="header-box">
  <div class="header-left">
    <a href="/index" class="logo">ErrorList</a>
  </div>
  <div class="header-right">
   
    <form  class="search" method="get" action="index">
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
        src="/pulic/img/serch1.png" 
        value="検索"
      >
  </form>
 
      <button 
        class="search-btn" 
        type="button" 
        onfocus="this.blur(); "
      >
          <img src="/pulic/img/serch1.png" class="search-img">
      </button>
        <!-- ログインしていればユーザーのアイコンが表示される -->
      @if(true == Auth::check())
      <a href="article.create" class="link-write-article">
      <button  class="link-write-article-btn">
        投稿する
      </button>
    </a>
        <img src="{{ $s3_profile_image ?? /pulic/img/default_image.png" 
          class="icon-img" style="margin: 0 10px;">
      <!-- ログインしていない場合、ログインと新規会員登録のリンクが表示される -->
      @else
      <a href="login" class="link">
        <button 
          id="btn" 
          type="button" 
          onfocus="this.blur();"
           >
          ログイン
        </button>
      </a>
     <span style="margin: 0 0 0 0;"></span>
      <a href="showregister" class="link">
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
        <a href="mypage.profile" class="nav-link">
          マイページ
        </a>
    </li>
      <li class="nav-list" >
        <a href="article.create" class="nav-link">投稿</a>
      </li>
      <li class="nav-list">
        <form action="logout"
        method="POST">
        @csrf
        <a 
        href=
        "logout" class="nav-link">
        ログアウト
      </a>
      </form>
    </li>
    </div>
  </ul>
</nav>
@endif
</header>
</template>