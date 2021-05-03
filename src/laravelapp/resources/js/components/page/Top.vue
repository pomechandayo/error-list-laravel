<template>
  <div>
    <head>
<link 
    rel="stylesheet" 
    href="/css/top.css">
<link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<div class="top-main">

<div class="top-article-container">
    <div class="search-result">
      <h2 class="search-article">
  　  </h2>
      <h2 class="search-article">
     
  　  </h2>
       <form action="index" method="get" class="tag-form">
         <input 
          class="tag-search" 
          type="text" 
          name="keyword"
          placeholder="tag:タグ名  キーワード"
          value=""
         >
         <input 
            class="tag-search-btn" 
            type="image" onfocus="this.blur(); " 
            src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/serch2.png" 
            value="検索"
          >
       </form>
     </div>
       
      <ul>
             
        <div class="top-article_box">
          <li class="top-article-user">
         
            <a href="userpage.show">
              <img src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/default_image.png" class="top-article-myimage">
            </a>
          
            <a href="mypage.profile">
              <img src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/default_image.png" class="top-article-myimage">
            </a>
          
          
            
            <div class="top-tag">
                              
            </div>
          </li>
                  <dl class="top-article-title"v-for="(info,index) in top.article_list" :key="index"
                  >
                  <dd></dd>
                  </dl>
                <li class="top-article-created_at">
                <div class="top-count-box">
                  <span class="top-like-count" style="margin-left: auto;">
                  </span>
                  <span class="top-like-count">コメント数
                  </span>
                </div>
                </li>
          </div>
         
      </ul>
      <div class="top-paginate">
          
      </div>
    </div>
  </div> 
  </div>
</template>

<script>
export default {
 data(){
    return{
      csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),

      top: []
    };
  },
  mounted() {
    this.$http.get("/api/index1").then(response => {
      this.top = response.data;
    });
  },
}
</script>