<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Login to Your Account</h2>

            <div v-if="Object.keys(authError).length > 0">
                {{ authError }}
            </div>
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition"
                >
                    Login
                </button>
            </form>

            <p class="text-center text-gray-600 dark:text-gray-400 text-sm mt-4">
                Don't have an account?
                <a href="#" class="text-blue-600 hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import {useAuthStore} from "../stores/authStore.js";
import router from "../router.js";
const email = ref('')
const password = ref('')
const authError = ref({})

const auth = useAuthStore();
const handleLogin = async () => {
   await auth.login({email: email.value, password: password.value}).then((res) => {
       router.push('/')
   }).catch((err) =>{
       authError.value = err?.response?.data
   })
}
</script>

<style scoped>
/* Add any additional component-scoped styles if needed */
</style>
