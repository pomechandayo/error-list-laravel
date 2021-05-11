<template>
<div>


<head>
<link rel="stylesheet" href="'/css/profile.css'">
<link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<div class="profile-container-lg">
  <div class="profile-container1">
    <div class="profile-box1">
      <img  class="profile-icon">
      <div class="profile-user-name">

      </div>
      <div class="profile-linkbox">
        <a href="" class="profile-link-menu">
        <div class="profile-article-total">

        </div>
        投稿した記事</a>
        <a href="" class="profile-link-menu">
            <div class="profile-article-total">

            </div>  
            コメントした記事
          </a>
       
        
          
        </div>
        <button class="profile-link-editprofile">
          <a href="" class="profile-link">プロフィール編集</a>
        </button>
      </div>
      </div>
      
      <!-- ここから記事一覧 -->
      <div class="profile-container2">


          <div class="profile-fillter-box">
            <a href="" class="profile-fillter-link">全て</a>
            <a href="" 
            class="profile-fillter-link">公開</a>
            <a href="" 
            class="profile-fillter-link">非公開</a>
          </div>


            <div class="profile-article-box">
              <li class="profile-article-user">
              <a href="">
                <img src="" class="profile-myimage"> 
              </a>

              <div class="mypage_article_tag">



              </div>
              </li>
              
              <a href="" class="profile-link-article">
              <li class="profile-article-title"></li>
              </a>
              <li class="profile-article-created_at">
                    
                    <div class="count_box">
                      <span class="mypage-like-count" style="margin-left: auto;">高評価
                      </span>
                      <span class="mypage-comment-count">コメント数
                      </span>
                    </div>
              </li>
          </div>

        <div class="profile-paginate">

        </div>

        <!-- ここまで記事一覧 -->
        </div>
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
      my_profile: []
    };
  },
  props: {
    auth:{
     type: Object|Array
    } ,
  },
 methods: {
   getArticles() {
   const url = '/api/mypage/show?page=' + this.page
   this.$http.get(url).then((response) => {
     this.myprofile = response.data.article_list;
   }).catch(error => {
     console.log(error.response)
   });
   },
   movePage(page) {
      this.page = page;
      this.getArticles();
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