require('./bootstrap');

import Vue from 'vue'
import  App from './components/HelloWorld.vue'

new Vue({
  el: 'app',
  components: {
    app: App
  }
})