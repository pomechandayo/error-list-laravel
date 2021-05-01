import Vue from 'vue'
import router from './router.js'
import axios from 'axios'

Vue.prototype.$http = axios;

new Vue({
  router: router,
}).$mount('#app')