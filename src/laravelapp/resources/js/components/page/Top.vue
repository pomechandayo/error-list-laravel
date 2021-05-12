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
<div class="top-main"
>
<div class="top-article-container">
    <div class="search-result">
      <h2 class="search-article">
 
  　  </h2>
      <h2 class="search-article">
     
  　  </h2>
       <form action="/index" method="get" class="tag-form">
       <input type="hidden" name="_token" :value="csrf" />
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
  
     <template v-for="(article_list,index) in top.data">
      
         <li id="li-none">

        
        <div class="top-article_box">
         

        
      
          <li class="top-article-user">
        
              <router-link :to="{name: 'userpage',
                 query: {userId: article_list.user.id}}"
                 v-if="article_list.user.id !== auth.id"
                >
              <img class="top-article-myimage"
              :src="article_list.user.profile_image">
             
            </router-link>
            <a 
             @click.stop.prevent="goUrlPage('/mypage/show')"
                 v-if="article_list.user.id === auth.id"
              >
              <img class="top-article-myimage"
              :src="article_list.user.profile_image">
            </a>

          <span>{{ article_list.user.name }}</span>
          
            <template v-for="(tags,index) in article_list.tags">
            <div class="top-tag">
              {{ tags.name}}
            </div>
            </template>
          </li>
                 <router-link :to="{name: 'article.show',
                 query: {articleId: article_list.id}}">
                  <li 
                  class="top-article-title"
                  >{{article_list.title}}</li>
                 </router-link>

                <div>
                <div class="top-article-created_at">
                  {{ article_list.created_at}}
                </div>
                <div class="top-count-box">

                  <div style="margin-left: auto;"
                  v-for="(likes,index) in article_list.likes" :key="`first-${index}`"
                  v-if="index > 0 && index < 2"
                  >
                    <div class="top-like-count">高評価数{{ Object.keys(article_list.likes).length }}
                    </div>
                  </div>

                  <div 
                  v-for="(comments,index) in article_list.comments" :key="`second-${index}`"
                  v-if="index > 0 && index < 2"
                  >
                      <div class="top-like-count">コメント数{{ Object.keys(article_list.comments).length }}
                      </div>
                  </div>
                </div>
                </div>
          </div>
      </li>
       
     </template>
    
      
      </ul>
 
          
    <Pagination :data=top @move-page="movePage($event)"/>

    </div>
  </div> 
  </div>
</template>

<script>
import Pagination from '../Pagination';

export default {
 data(){
    return{
      csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),

      page: 1,
      top: [],
      keywords: [],
      showUrl: [],
    };
  },
  props: {
    auth:{
     type: Object|Array
    } ,
  },
 methods: {
   getArticles() {
   const url = '/api/index?page=' + this.page
   this.$http.get(url).then((response) => {
     this.top = response.data.article_list;
   }).catch(error => {
     console.log(error.response)
   });
   },
   movePage(page) {
      this.page = page;
      this.getArticles();
  },
   goUrlPage(url) {
      this.$router.push(url);
  },
 },
  mounted() {
    this.getArticles();
  },
  components: {
    Pagination
  },
  
}
</script>