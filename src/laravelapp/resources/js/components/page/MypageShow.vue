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
      <img  :src="profile_image" class="profile-icon">
      <div class="profile-user-name">
        {{ user_name }}
      </div>
      <div class="profile-linkbox">
        <div
        @click.prevent="clickGetUserArticles()"
        class="profile-link-menu"
        >
          <div class="profile-article-total">
            {{ article_count }}
          </div>
            投稿した記事
        </div>
        <div
        @click.prevent="clickGetUserArticles('user_comments')"
        class="profile-link-menu"
        >
          <div class="profile-article-total">
            {{ comment_count }}
          </div>  
            コメントした記事
         </div>          
        </div>

        <button class="profile-link-editprofile">
          <a  
          @click.stop.prevent="goUrlPage('/mypage/edit')"
          class="profile-link"
          >
            プロフィール編集
          </a>
        </button>
      </div>
      </div>
      

      <!-- ここから記事一覧 -->
      <div 
      class="profile-container2"
      >
        <template v-for="(article,index) in article_list.data ">
            <div class="profile-article-box">
              <li class="profile-article-user">
              <router-link :to="{name: 'userpage',
                 query: {userId: article.user.id}}"
                >
                <img 
                  :src="article.user.profile_image" 
                  class="profile-myimage"
                > 
              </router-link>
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
      my_profile:    [],
      user_name:     [],
      profile_image:  [],
      article_count: [],
      comment_count: [],
      article_list:  [],
      keyword:       '',
    };
  },
  props: {
    auth:{
     type: Object|Array
    },
  },
 methods: {
    getProfileImage() {
      const data = {
        userid: this.auth.id
      }
     const self = this;
     const url ='/api/profile/' + this.auth.id;
     this.$http.get(url)
      .then(response => {
        self.user_name = response.data.name;
        self.profile_image = response.data.profile_image;
      }).catch( error => {console.log(error)});
    },
    clickGetUserArticles(keyword) {

      this.keyword = keyword;
      const url = '/api/mypage/show/' + this.auth.id + '/' + keyword + '?page=' + this.page;
      const self= this;
      this.$http.get(url)
      .then(response => {
        self.article_count = response.data.article_count;
        self.comment_count = response.data.comment_count;
        self.article_list  = response.data.article_list;

      })
      .catch( error => {console.log(error)});

      this.page = 1;
   },
   movePage(page) {
      this.page = page;
      this.clickGetUserArticles(this.keyword);
  },
   goUrlPage(url) {
      this.$router.push(url);
  },
  },
  mounted() {
    this.getProfileImage();
    this.clickGetUserArticles(this.keyword);
  },
  components: {
    Pagination
  },
  
}
</script>