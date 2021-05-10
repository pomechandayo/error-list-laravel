<template>
  <div>
       
      <template v-if="user_id !== null">

        <span 
        class="likes"
        v-if="status === false"
        >

          <i 
          class="like-toggle"
          @click.prevent="likeCheck"
          >
            高評価
          </i>
          <span class="like-counter">
            {{ likeCount }}
          </span>

        </span>
        <span class="likes" v-else>

          <i
          class="like-toggle liked"
          @click.prevent="likeCheck"
          > 高評価</i>

          <span class="like-counter">
            {{ likeCount }}
          </span>
        </span>

      </template>
      
    <template v-if="user_id === null">
      <span class="likes">
        <span class="like-toggle-guest">高評価</span>
        <span class="like-counter">
          {{ likeCount }}
        </span>
       </span>
    </template>
      
  </div>
</template>

<script>
  export default{
    props: ['article_id','user_id'],
   data() {
      return{
      status: false,
      likeCount: []
       }
    },
    methods: {
      firstCheck() {
        const article_id = this.article_id;
        const user_id    = this.user_id;
        const Url = "/api/like/" + article_id + "/" + user_id +"/likeFirstCheck";
        console.log(Url);
        axios.get(Url)
        .then(res => {
          if(res.data[0] === true){
            console.log(res)
            this.status = res.data[0];
            this.likeCount  = res.data[1]
          }else{
            console.log(res)
            this.status = res.data[0]
            this.likeCount  = res.data[1]
          }
        }).catch(error => {
          console.log(error)
        });
      },
      likeCheck() {
        const article_id = this.article_id
        const user_id = this.user_id
         const Url = "/api/like/" + article_id + "/" + user_id +"/likeCheck";

         axios.get(Url)
         .then(res => {
           if(res.data[0] === true){
             this.status = res.data[0]
             this.likeCount  = res.data[1]
           }else{
             this.status = res.data[0]
             this.likeCount  = res.data[1]
           }
         }).catch(error => {
           console.log(error)
         });
      },
       getLikeCount() {

      const likeCountUrl = '/api/likeCount/' + this.$route.query.articleId;
      let self = this;
      
      this.$http.get(likeCountUrl)
      .then( response => {
        
        self.likeCount = response.data;
      })
      .catch(error => {
        console.log(error)
      }); 
    },
    a() {
      console.log(this.article_id);
    }
    },
    mounted() {
      this.a();
      this.firstCheck();
  },
  }
</script>