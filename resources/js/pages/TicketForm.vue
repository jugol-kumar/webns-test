<template>
    <Main>
        <form
            @submit.prevent="submitForm"
            class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto space-y-6"
        >
            <!-- Subject -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Ticket Subject</label>
                <input
                    type="text"
                    v-model="ticket.subject"
                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter subject"
                />
            </div>

            <!-- Description -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Ticket Description</label>
                <textarea
                    v-model="ticket.description"
                    rows="4"
                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Describe your issue"
                ></textarea>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category</label>
                <select
                    v-model="ticket.category"
                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option disabled value="">Select Category</option>
                    <option>Technical</option>
                    <option>Billing</option>
                    <option>General</option>
                </select>
            </div>

            <!-- Priority -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Priority</label>
                <select
                    v-model="ticket.priority"
                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option disabled value="">Select Priority</option>
                    <option value="high">Low</option>
                    <option value="medium">Medium</option>
                    <option value="low">High</option>
                </select>
            </div>

            <!-- Attachment -->
<!--            <div>-->
<!--                <label class="block text-gray-700 font-medium mb-2">Attachment (optional)</label>-->
<!--                <input type="file" @change="handleFileUpload" class="w-full" />-->
<!--            </div>-->

            <!-- Submit -->
            <div class="text-right">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Submit Ticket
                </button>
            </div>
        </form>
    </Main>
</template>

<script setup>
import { ref} from 'vue'
import Main from '../layouts/Main.vue'
import axios from "axios";
import {useAuthStore} from "../stores/authStore.js";
import {useToast} from "vue-toastification";
const toast = useToast()
const ticket = ref({
    subject: '',
    description: '',
    category: '',
    priority: '',
    attachment: null,
    user_id: useAuthStore()?.user?.id
})

function handleFileUpload(event) {
    ticket.attachment = event.target.files[0]
}

function submitForm() {
    axios.post('/api/tickets', ticket.value)
    ticket.value = {}
    toast.success('Ticket created')
}
</script>
