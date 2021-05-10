<template>
  <header>
<div class="header-box">
  <div class="header-left">
    <a href="" class="logo"
     @click.stop.prevent="goUrlPage('/index')"
        >ErrorList</a>
  </div>
  <div class="header-right">
   
    <form  class="search" method="get" action="index">
      <input type="hidden" name="_token" :value="csrf" />
      <input 
        class="search-input-pc" 
        type="text" 
        placeholder="tag:タグ名 キーワード"
        name="keyword"
      >
      <input 
      @click="toggle('1001')"
        class="search-btn-pc" 
        type="image" onfocus="this.blur(); " 
        src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/serch1.png" 
        value="検索"
      >
  </form>
      <button 
        @click="toggle('1001')"
        class="search-btn" 
        type="button" 
        onfocus="this.blur(); "
      >
          <img 
           src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/serch1.png" class="search-img">
      </button>
 
        <!-- ログインしていればユーザーのアイコンが表示される -->
      <a  class="link-write-article"
       v-if="auth.length !== 0">
          <button  class="link-write-article-btn"
          @click.stop.prevent="goUrlPage('/article/create')"
          >
            投稿する
          </button>
       </a>
    
        <img 
        @click="toggle('1000')"
        :src="userImage" 
          class="icon-img" style="margin: 0 10px;"
          v-if="auth.length !== 0">
     
        <!-- ログインしていない場合、ログインと新規会員登録のリンクが表示される -->
   
      <a href="" class="link"  v-if="auth.length === 0">
        <button 
          id="btn" 
          type="button" 
          onfocus="this.blur();"
          @click.stop.prevent="goUrlPage('/login')"
           >
          ログイン
        </button>
      </a>
     <span style="margin: 0 0 0 0;"
     v-if="auth.length === 0"></span>
      <a href="" class="link"
      v-if="auth.length === 0">
        <button 
        id="btn" 
        type="button" 
        onfocus="this.blur();"
        @click.stop.prevent="goUrlPage('/register')"
        >
        会員登録</button></a>
      
  </div>
</div>

<nav id="nav1" v-if="auth.length !== 0"
v-show="show_contents.indexOf('1000') >= 0"> 
  <ul class="nav-ul">
      <li class="nav-list" >
        <a class="nav-link"
        @click.stop.prevent="goUrlPage('/mypage/profile')">
          マイページ
        </a>
    </li>
      <li class="nav-list" >
        <a href="/article/create" class="nav-link"
        @click.stop.prevent="goUrlPage('/article/create')">
          投稿
        </a>
      </li>
      <li class="nav-list">
        <a href="/logout" class="nav-link">
            ログアウト
        </a>
    </li>
    </div>
  </ul>
</nav>

<form  id="search1" action="index" method="get" v-show="show_contents.indexOf('1001') >= 0">
     <input type="hidden" name="_token" :value="csrf" />
    <input class="search-input" type="text" placeholder="検索キーワード" name="keyword">
    <input class="search-btn1" type="image" onfocus="this.blur(); " 
    src="https://was-and-infra-errorlist-laravel.s3-ap-northeast-1.amazonaws.com/serch2.png" 
    style="
    height: 25px;
    width: 25px;
    background-color: #fff;
    border: 0 none;
    margin-left: 5px;
    ">
</form>
</header>

</template>

<script>
  export default {
    data() {
      return {
        csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      show_contents: [],
      show_menu: [],
      userid: "",
      userImage: "",
      };
      
    },
    props: {
      auth:{
        type: Object|Array
      } 
    },
    methods: {
      toggle: function (data) {
        if (this.show_contents.indexOf(data) >= 0) {
          this.show_contents = this.show_contents.filter(n => n !== data)
        }else {
          this.show_contents.push(data)
        }
      },
     goUrlPage(url) {
       this.$router.push(url);
     },
    getProfileImage() {
      const data = {
        userid: this.auth.id
      }
     const self = this;
     const Url ='/api/profile/' + this.auth.id;
     this.$http.get(Url)
      .then(response => {
        self.userImage = response.data.profile_image;
      }).catch( error => {console.log(error);});
    }
    },
    created() {
      this.getProfileImage()
    },
}
</script>