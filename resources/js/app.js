import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import App from './components/App.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Articles from './components/Articles.vue';
import ArticleForm from './components/ArticleForm.vue';
import ArticleDetail from './components/ArticleDetail.vue';
import AdminLogin from './components/AdminLogin.vue';
import NewsPage from './components/NewsPage.vue';

axios.defaults.baseURL = '/api';
axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: Articles },
        { path: '/news', component: NewsPage },
        { path: '/login', component: Login },
        { path: '/register', component: Register },
        { path: '/articles/new', component: ArticleForm },
        { path: '/articles/:id/edit', component: ArticleForm, props: true },
        { path: '/articles/:id', component: ArticleDetail, props: true },
        { path: '/admin-login', component: AdminLogin },
    ],
});

createApp(App).use(router).mount('#app');
