import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from './pages/dashboard.vue';
import Org from './pages/org.vue'
import OrgView from './pages/org/view.vue'
import User from './pages/user.vue'

Vue.use(VueRouter);

let app_url = '/app'

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: `${app_url}/dashboard`,
            name: 'dashboard',
            component: Dashboard
        },
        {
            path: `${app_url}/org`,
            name: 'org',
            component: Org
        },
        {
            path: `${app_url}/org/view/:id`,
            name: 'org-view',
            component: OrgView
        },
        {
            path: `${app_url}/user`,
            name: 'user',
            component: User
        },
    ]
});

export default router;