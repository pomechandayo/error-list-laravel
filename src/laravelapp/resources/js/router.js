import Vue from 'vue'
import Router from 'vue-router'
import Register from './components/page/Register'
import Login from './components/page/Login'
import Top from './components/page/Top'
import Show from './components/page/Show'
import MypageShow from './components/page/MypageShow'
import ArticleLike from './components/ArticleLike'
import axios from 'axios'

Vue.use(Router)
Vue.prototype.$http = axios;

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
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
      path: '/index',
      name: 'index',
      component: Top
    },
    {
      path: '/article/show',
      name: 'article.show',
      component: Show,
      
    },
    {
      path: '/article/show/articleLike',
      name: 'article-like',
      component: ArticleLike
    },
    {
      path:'/mypage/show',
      name: 'mypage-show',
      component: MypageShow,
    },

  ]
})