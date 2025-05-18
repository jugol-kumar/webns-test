<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white flex flex-col justify-between p-6 shadow-lg">
            <div>
                <h2 class="text-2xl font-bold mb-8 tracking-wide">🎫 Support Desk</h2>
                <nav class="space-y-3">
                    <router-link
                        to="/"
                        class="block py-2 px-3 rounded-md hover:bg-blue-700 transition flex items-center gap-2"
                        :class="{ 'bg-blue-700': isActive('/') }"
                    >
                        <span>📄</span> Create Ticket
                    </router-link>
                    <router-link
                        to="/list"
                        class="block py-2 px-3 rounded-md hover:bg-blue-700 transition flex items-center gap-2"
                        :class="{ 'bg-blue-700': isActive('/list') }"
                    >
                        <span>📋</span> Ticket List
                    </router-link>
                    <router-link
                        to="/chat"
                        class="block py-2 px-3 rounded-md hover:bg-blue-700 transition flex items-center gap-2"
                        :class="{ 'bg-blue-700': isActive('/chat') }"
                    >
                        <span>💬</span> Chat
                    </router-link>
                </nav>
            </div>

            <div class="pt-6 border-t border-blue-700 mt-8">
                <button
                    @click="handleLogout"
                    class="w-full text-left text-sm text-red-300 hover:text-white transition flex items-center gap-2"
                >
                    <span>🚪</span> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-800" v-if="title"> {{ title }} </h1>
            </header>

            <!-- Form Container -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/authStore.js'
import router from '../router.js'

defineProps({
    title: String
})

const route = useRoute()
const auth = useAuthStore()

const handleLogout = () => {
    if (confirm('Sure! Want to logout?')) {
        auth.logout().then(() => router.push({ name: 'login' }))
    }
}

const isActive = (path) => route.path === path
</script>
