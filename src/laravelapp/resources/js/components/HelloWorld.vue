<template>
  <div>
    <the-header></the-header>
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
     import TheFooter from "./layout/TheFooter";
     import TheHeader from "./layout/TheHeader"; 

    export default {
      components: {
        TheFooter,
        TheHeader,
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