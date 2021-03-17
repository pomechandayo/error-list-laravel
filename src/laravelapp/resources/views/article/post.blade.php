@extends('layouts.app_content')

@section('title')
投稿ページ
@endsection

@section('content')
<head>
  <link rel="stylesheet" href="{{ asset('/css/article.css') }}">
</head>

<form action="" method="post" class="article-form">
@csrf
  <input type="text" name="article-title" class="article-title" placeholder="タイトル">
  <input type="text" name="article-tag" class="article-tag"  placeholder="タグをカンマ区切りで5つまで(PHP,Ruby,Javaなど)">
  <div class="tab-bar">
    <div class="tab-bar-text">本文</div>
    <div class="tab-bar-preview">プレビュー</div>
  </div>
  <textarea name="article" id="markdown-editor-textarea">

  </textarea>
  <div id="markdown-preview"></div>

  <div class="btn-bar">
    <button type="submit" class="article-post-btn">投稿</button>
  </div>

</form>


@endsection