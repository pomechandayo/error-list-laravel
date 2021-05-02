import Vue from 'vue'
import Router from 'vue-router'
import App from './components/HelloWorld'
import Register from './components/page/Register'
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
    {
      path: '/register',
      name: 'register',
      component: Register
    },
  ]
})