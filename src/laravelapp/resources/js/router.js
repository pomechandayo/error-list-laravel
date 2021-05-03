import Vue from 'vue'
import Router from 'vue-router'
import Register from './components/page/Register'
import Login from './components/page/Login'
import Top from './components/page/Top'
import axios from 'axios'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
    path: '/login',
    name: 'login',
    component: Login
    },
    {
    path: '/index1',
    name: 'index',
    component: Top
    },
  ]
})