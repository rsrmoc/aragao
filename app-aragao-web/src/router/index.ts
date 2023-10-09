import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import LoginPage from '@/views/LoginPage.vue';
import HomePage from '@/views/HomePage.vue';
import ObrasPage from '@/views/obras/ObrasPage.vue';
import ChatsPage from '@/views/chats/ChatsPage.vue';
import AccountPage from '@/views/account/AccountPage.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Login',
    component: LoginPage,
  },
  {
    path: '/home',
    name: 'Home',
    redirect: '/home/obras',
    component: HomePage,
    children: [
      {
        path: 'obras',
        name: 'ObrasPage',
        component: ObrasPage
      },
      {
        path: 'chats',
        name: 'ChatsPage',
        component: ChatsPage
      },
      {
        path: 'account',
        name: 'AccountPage',
        component: AccountPage
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
