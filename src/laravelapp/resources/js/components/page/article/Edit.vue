<template>
<div>

<form action="/article/update" method="post" class="article-form">
     
      <input type="hidden" name="_token" :value="csrf" />

      <input 
      type="hidden" name="article_id" 
      :value="article_data.id" 
      >
       
      <input 
      type="text" 
      name="title" 
      class="article-title" 
      placeholder="タイトル" 
      :value="article_data.title"
      >


    <template v-for="(error,index) in errors ">
        <div class="create-error">※{{  error }}</div>
    </template>


       

      <input 
      type="text" 
      name="tags" 
      class="article-tag" 
      placeholder="先頭に#をつけてタグ5つまでつけられます(#PHP,#Ruby,#Javaなど)"
      :value="tag"
      >

      <div class="tab-bar">
        <div class="tab-bar-text">本文</div>
        <div class="tab-bar-preview">プレビュー</div>
      </div>
      <textarea id="markdown-editor-textarea" name="body" placeholder="">{{ article_data.body }}</textarea>



    <div id="markdown-preview" v-html="article_parse_body">

    </div>
  
  <div class="btn-bar">
    <button type="submit" class="article-post-btn">更新</button>
    
  </div>
</form>

</div>
</template>
<script>

export default {
 data(){
    return{
      csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),

      article_data:       [],
      article_parse_body: [],
      tag:                [],
    }
 },
  props: {
    errors: {
      type: Array|Object
    }
  },
 methods: {
   getEditData() {

     const url = '/api/article/' + this.$route.query.articleId + '/edit';
     const self = this;

     this.$http.get(url)
     .then( response => {

       self.article_parse_body = response.data[0];
       self.article_data       = response.data[1];
       self.tag = response.data[2];

     }).catch( error => {
       console.log(error);
     });
   },
 },
 mounted() {
  this.getEditData();

  $(function () {
  $('#markdown-editor-textarea').keyup(function () {
    let html = marked($(this).val());
    $('#markdown-preview').html(html);
  });
  });

 }

 }
</script>