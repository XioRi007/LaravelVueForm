import { createRouter, createWebHistory } from 'vue-router'
import MembersView from '../views/MembersView.vue'
import LoginView from "../views/LoginView.vue";
import AdminView from "../views/AdminView.vue";
import MemberRegisterView from "../views/MemberRegisterView.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
      {
          path: '/',
          name: 'main',
          component: MemberRegisterView
      },
      {
          path: '/members',
          name: 'members',
          component: MembersView
      },
      {
          path: '/login',
          name: 'login',
          component: LoginView
      },
      {
          path: '/admin',
          name: 'admin',
          component: AdminView
      },
      {
          path: '/:pathMatch(.*)',
          redirect: '/'
      }
  ]
})

export default router
