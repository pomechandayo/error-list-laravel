@extends('layouts.app_content')

@section('title')
投稿ページ
@endsection

@section('content')
<head>
  <link 
    rel="stylesheet" 
    href="{{ asset('/css/create.css') }}"
  >
</head>

<form action="/article" method="post" class="article-form">
@csrf
  <input 
    type="text" 
    name="title" 
    class="article-title" 
    placeholder="タイトル" 
    value="{{ old('title')}}"
  >

  <input 
    type="text" 
    name="tags" 
    class="article-tag"  
    placeholder="先頭に#をつけてタグ5つまでつけられます(#PHP,#Ruby,#Javaなど)"
    value="{{ old('tag') }}">
  <div class="tab-bar">
    <div class="tab-bar-text">本文</div>
    <div class="tab-bar-preview">プレビュー</div>
  </div>
  <textarea 
  id="markdown-editor-textarea" name="body">
</textarea>

<div id="markdown-preview">
</div>

  <div class="btn-bar">
    <button type="submit" class="article-post-btn">投稿</button>
  </div>



</form>
<script>
  const 
</script>

@endsection