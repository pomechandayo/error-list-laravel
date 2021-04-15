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

<form action="{{ route('article.update',$article_data->id)}}" method="post" class="article-form">
      @csrf
      @method('patch')
      <input type="text" name="title" class="article-title" placeholder="タイトル" value="{{ old('title')??$article_data->title}}">
      
      @error('title')
        <div class="create-error">※{{ $message }} </div>
      @enderror
      @error('tags')
        <div class="create-error">※{{ $message }}</div>
      @enderror
      @error('body')
        <div class="create-error">※{{ $message }}</div>
      @enderror
      
      @if($tag === null)
        <input 
        type="text" 
        name="tags" 
        class="article-tag"  
        placeholder="先頭に#をつけてタグ5つまでつけられます(#PHP,#Ruby,#Javaなど)"
        value="{{ old('tag')}}">
      @else
        <input 
        type="text" 
        name="tags" 
        class="article-tag"  
        placeholder="先頭に#をつけてタグ5つまでつけられます(#PHP,#Ruby,#Javaなど)"
        value="#{{ old('tag') ?? $tag }}">
      @endif

      
    <div class="tab-bar">
    <div class="tab-bar-text">本文</div>
    <div class="tab-bar-preview">プレビュー</div>
</div>
<textarea id="markdown-editor-textarea" name="body" placeholder="本文を書いてください">{{old('body') ?? $article_data->body}}</textarea>



<div id="markdown-preview">
{!! $article_parse_body !!}
</div>
  
  <div class="btn-bar">
    <button type="submit" class="article-post-btn">更新</button>
    
  </div>
  
</form>

@endsection