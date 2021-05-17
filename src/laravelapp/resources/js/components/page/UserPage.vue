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
      <img :src="user_data.profile_image" class="profile-icon">
      <div class="profile-user-name">
        {{ user_data.name }}
      </div>
      <div class="profile-linkbox">
        <div class="profile-link-menu">
          <div class="profile-article-total">
            {{ article_count }}
          </div>
            投稿した記事
          </div>
              
        </div>
      </div>
      </div>
      

      <!-- ここから記事一覧 -->
      <div class="profile-container2">
        <template v-for="(article,index) in article_list.data ">
            <div class="profile-article-box">
              <li class="profile-article-user">
              <a >
                <img 
                  :src="article.user.profile_image" 
                  class="profile-myimage"
                > 
              </a>

              <div class="article-user-name">
                {{ article.user.name }}
              </div>
            <template v-for="(tags,index) in article.tags">
              <div class="mypage_article_tag">
                {{ tags.name }}
              </div>
            </template>
              </li>
              
              <router-link :to="{name: 'article.show',
                query: {articleId: article.id}}"
                class="profile-link-article"
              >
                <li class="profile-article-title">
                  {{ article.title }}
                </li>
              </router-link>
              <li class="profile-article-created_at">
                    
                    <div class="count_box">
                      <span class="mypage-like-count" style="margin-left: auto;">
                        高評価
                        {{ Object.keys(article.likes).length}}
                        件
                      </span>
                      <span class="mypage-comment-count">
                        コメント数
                        {{ Object.keys(article.comments).length}}
                        件
                      </span>
                    </div>
              </li>
          </div>
        </template>

        <div class="profile-paginate">
          <Pagination 
            :data=article_list
            @move-page=movePage($event) 
           />
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
      article_count: [],
      article_list:  [],
      user_data:     [],
    };
  },
  props: {
    auth:{
     type: Object|Array
    },
  },
 methods: {

    getUserArticles() {

      const url = '/api/userpage/' + this.$route.query.userId + '?page=' + this.page;
      const self= this;

      this.$http.get(url)
      .then(response => {
        self.article_count = response.data.article_count;
        self.article_list  = response.data.article_list;
        self.user_data     = response.data.user_data;
        
      })
      .catch( error => {console.log(error)});
   },
   movePage(page) {
      this.page = page;
      this.getUserArticles();
  },
   goUrlPage(url) {
      this.$router.push(url);
  },
  },
  mounted() {
    this.getUserArticles();
  },
  components: {
    Pagination
  },
  
}
</script>