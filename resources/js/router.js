import {createRouter, createWebHistory} from "vue-router";
import TicketForm from "./pages/TicketForm.vue";
import Login from "./pages/Login.vue";
import {useAuthStore} from "./stores/authStore.js";
import TicketList from "./pages/TicketList.vue";
import Chat from "./pages/Chat.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: TicketForm,
            meta: {requiresAuth: true}
        },
        {
            path: '/list',
            name: 'tasks.list',
            component: TicketList,
            meta: {requiresAuth: true}
        },
        {
            path: '/chat',
            name: 'chat',
            component: Chat,
            meta: {requiresAuth: true}
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
    ]
})


router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore()
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    if (!auth.user) {
        try {
            await auth.fetchUser()
        } catch (error) {
            if (requiresAuth) {
                return next({ name: 'login' })
            }
        }
    }
    if (requiresAuth && !auth.user) {
        return next({ name: 'login' })
    }
    if (!requiresAuth && auth.user) {
        if (from.name && from.fullPath !== to.fullPath) {
            return next(from.fullPath)
        } else {
            return next({ name: 'home' })
        }
    }
    return next()
})
export default router
