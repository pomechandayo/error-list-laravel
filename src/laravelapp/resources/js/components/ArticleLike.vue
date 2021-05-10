<template>
  <div>
       
         
        <span class="likes">
                    <i class="like-toggle liked" 
                    @click="clickLike"
                    >
                      高評価
                    </i>

                    <span class="like-counter">
                      {{ likeCount }}
                    </span>
        </span>
           
            
  </div>
</template>

<script>
  export default{
    props: {
      initiallsLikedBy: {
        type: Boolean,
        default: false,
      },
      intialCountLikes: {
        type: Number,
        default: 0,
      },
      authorized: {
        type: Boolean,
        default: false,
      },
      endpoint: {
        type: String,
      },
    },
   data() {
      return{
      isLikedBy: this.initiallsLikedBy,
      countLikes: this.initialCountLikes,

      likeCount: [],
       }
    },
    methods: {
      clickLike() {
        if(!this.authorized) {
          alert('高評価機能はログイン中のみ使用できます')
          return
        }

        this.isLikedBy
        ?this.unlike()
        :this.like()
      },
      async like() {
        const response = await axios.put(this.endpoint)

        this.isLikeBy = true
        this.countLikes = response.data.countLikes
      },
      async unlike() {
        const response = await axios.delete(this.endpoint)

        this.isLikeBy = false
        this.countLikes = response.data.countLikes
      },
       getLikeCount() {

      const likeCountUrl = '/api/likeCount/' + this.$route.query.articleId;
      let self = this;
      
      this.$http.get(likeCountUrl)
      .then( response => {

        self.likeCount = response.data;

      }).catch(error => {
     console.log(error)
      }); 
    },
    },
    mounted() {
      this.getLikeCount();
  },
  }
</script>