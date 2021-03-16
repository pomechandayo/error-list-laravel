@extends('layouts.app_content')

@section('title')
投稿ページ
@endsection

@section('content')
<textarea name="article" id="markdown-editor-textarea" cols="30" rows="10">

</textarea>
<textarea name="" id="markdown-preview" cols="30" rows="20" style="margin-top:50px;"></textarea>



<script>
  $(function () {
  $('#markdown-editor-textarea').keyup(function () {
    let html = marked($(this).val());
    $('#markdown-preview').html(html);
  });
  });

</script>

@endsection