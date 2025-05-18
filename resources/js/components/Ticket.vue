<template>
    <div class="w-full mx-auto p-6 bg-white shadow rounded-lg border border-gray-200">
        <!-- Header -->
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">📄 {{ ticket.subject }}</h2>

        <!-- Ticket Info -->
        <div class="space-y-2 text-sm text-gray-700">
            <p><strong>ID:</strong> {{ ticket.id }}</p>
            <p><strong>Category:</strong> {{ ticket.category }}</p>
            <p>
                <strong>Priority:</strong>
                <span
                    :class="{
            'text-green-600': ticket.priority === 'low',
            'text-yellow-600': ticket.priority === 'medium',
            'text-red-600': ticket.priority === 'high'
          }"
                >
          {{ ticket.priority }}
        </span>
            </p>
            <p>
                <strong>Status:</strong>
                <span
                    :class="{
            'text-blue-600': ticket.status === 'open',
            'text-gray-600': ticket.status === 'closed'
          }"
                >
          {{ ticket.status }}
        </span>
            </p>
            <p><strong>User ID:</strong> {{ ticket.user_id }}</p>
            <p><strong>Created:</strong> {{ formatDate(ticket.created_at) }}</p>
            <p><strong>Updated:</strong> {{ formatDate(ticket.updated_at) }}</p>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">📝 Description</h3>
            <p class="bg-gray-50 border border-gray-200 p-4 rounded text-gray-800 whitespace-pre-wrap">
                {{ ticket.description }}
            </p>
        </div>

        <!-- Attachment -->
        <div v-if="ticket.attachment" class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700">📎 Attachment</h3>
            <a :href="ticket.attachment" target="_blank" class="text-blue-600 underline">View Attachment</a>
        </div>

        <!-- Comments -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">💬 Comments</h3>

            <div v-if="ticket?.comments.length === 0" class="text-gray-500 mb-4">
                No comments yet.
            </div>

            <ul class="space-y-4">
                <li
                    v-for="comment in ticket?.comments"
                    :key="comment.id"
                    class="p-4 bg-gray-50 border border-gray-200 rounded"
                >
                    <p class="text-gray-800 whitespace-pre-wrap">{{ comment.comment }}</p>
                    <div class="text-xs text-gray-500 mt-2">
                        By {{ comment.user?.name }} · {{ formatDate(comment.created_at) }}
                    </div>
                </li>
            </ul>

            <!-- Add New Comment -->
            <div class="mt-6">
        <textarea
            v-model="newComment"
            placeholder="Write your comment..."
            rows="3"
            class="w-full p-3 border border-gray-300 rounded resize-none"
        ></textarea>
                <button
                    @click="addComment(ticket?.id)"
                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Post Comment
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from "axios";
import {useAuthStore} from "../stores/authStore.js";

const props = defineProps({
    ticket: Object
})

const comments = ref([
    // Example structure:
    // { id: 1, text: 'Initial comment', user: 'Alice', created_at: '2025-05-18T12:00:00Z' }
])

const newComment = ref('')

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleString()
}

async function addComment(tickitId) {
    const text = newComment.value.trim()
    if (!text) return

    comments.value.push({
        id: Date.now(),
        text,
        user: 'Current User', // Replace with actual logged-in user's name
        created_at: new Date().toISOString()
    })

    await axios.post('/api/comment', {
        comment: newComment.value,
        ticket_id: tickitId,
        user_id: useAuthStore()?.user?.id
    })

    newComment.value = ''
}
</script>
