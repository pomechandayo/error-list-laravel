import Vue from 'vue'
import router from './router.js'
import axios from 'axios'
import MainPage from './components/page/MainPage'

Vue.prototype.$http = axios;


new Vue({
  router: router,
  components: {
  app: MainPage
  }
}).$mount('#app')