<template>
  <div>
    
  </div>
</template>
<script>
    export default {
      props: {
        auth: {
          type: Object|Array
      }
      },
      data() {
        return {
          users: []
        }
      },
       mounted() {
         this.$http.get('index2').then(response => this.users = response.data)
       },
   
    };
    
</script>