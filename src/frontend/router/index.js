import Vue from 'vue'
import Router from 'vue-router'
import Home from 'frontend/pages/Home.vue'
import Profile from 'frontend/pages/Profile.vue'
import UserGroup from 'frontend/pages/User/_id.vue'

Vue.use(Router)
Vue.use(require('vue-moment'));

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/profile',
      name: 'Profile',
      component: Profile
    },
    {
      path: '/user-group/:id',
      name: 'UserGroup',
      component: UserGroup
    }
  ]
})
