<template>
  <div>
    <the-header :auth="auth" />
    Hello World
    Hello World
    Hello World

    <ul>
    <li v-for="user in users">{{ user.name }}</li>
    </ul>
    <the-footer></the-footer>
  </div>
</template>
<script>
     import TheHeader from "./layout/TheHeader"; 
     import TheFooter from "./layout/TheFooter";

    export default {
      components: {
        TheHeader,
        TheFooter,
      },
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