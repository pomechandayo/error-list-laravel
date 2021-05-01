import Vue from 'vue'
import Router from 'vue-router'
import App from './components/HelloWorld.vue'
import axios from 'axios'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/index1',
      name: 'index1',
      component: App
    },
  ]
})