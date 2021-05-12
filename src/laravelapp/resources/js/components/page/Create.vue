<template>
<div>

<form action="/article" method="post" class="article-form">
     
      <input type="text" name="title" class="article-title" placeholder="タイトル" value="">


        <div class="create-error">※ </div>


        <div class="create-error">※</div>


        <div class="create-error">※</div>

      <input type="text" name="tags" class="article-tag" placeholder="先頭に#をつけてタグ5つまでつけられます(#PHP,#Ruby,#Javaなど)"
      value="">
      <div class="tab-bar">
        <div class="tab-bar-text">本文</div>
        <div class="tab-bar-preview">プレビュー</div>
      </div>
      <textarea id="markdown-editor-textarea" name="body" placeholder="本文を書いてください"></textarea>



    <div id="markdown-preview">
    </div>
  
  <div class="btn-bar">
    <button type="submit" class="article-post-btn">投稿</button>
    
  </div>
</form>

</div>
</template>