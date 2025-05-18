<template>
    <Main>
        <div class="flex h-[90vh] bg-gray-50 font-sans text-sm">
            <!-- 🔹 Sidebar -->
            <aside class="w-72 bg-white border-r shadow-sm">
                <div class="p-4 border-b text-lg font-semibold text-gray-800">
                    Conversations
                </div>
                <ul class="divide-y max-h-[80vh] overflow-y-auto">
                    <li
                        v-for="user in users"
                        :key="user.id"
                        @click="selectUser(user)"
                        :class="[
                          'p-4 cursor-pointer hover:bg-blue-50 transition-all',
                          selectedUser?.id === user.id ? 'bg-blue-100 text-blue-700' : 'text-gray-800'
                        ]"
                    >
                        <div class="font-medium">{{ user.name }}</div>
                        <div class="font-normal text-xs">{{ user.email }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ user.lastMessage }}</div>
                    </li>
                </ul>
            </aside>

            <!-- 🔸 Chat Panel -->
            <main class="flex flex-col flex-1">
                <!-- Header -->
                <div class="bg-white px-6 py-4 border-b flex items-center justify-between shadow-sm">
                    <h2 class="font-semibold text-gray-800">
                        {{ selectedUser ? selectedUser.name : 'Select a conversation' }}
                    </h2>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-100">
                    <div
                        v-for="msg in filteredMessages"
                        :key="msg.id"
                        :class="[
                            'max-w-xs px-4 py-2 rounded-xl shadow-sm',
                            msg.user.id === auth.user.id
                              ? 'bg-blue-600 text-white ml-auto'
                              : 'bg-white text-gray-800 mr-auto'
                          ]"
                    >
                        {{ msg.text }}
                        <div class="text-[11px] mt-1 opacity-60">
                            {{ formatTime(msg.timestamp) }}
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <form @submit.prevent="sendMessage" class="bg-white px-6 py-4 border-t flex gap-3">
                    <input
                        v-model="message"
                        type="text"
                        placeholder="Type your message..."
                        class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :disabled="!selectedUser"
                    />
                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition"
                        :disabled="!message.trim() || !selectedUser"
                    >
                        Send
                    </button>
                </form>
            </main>
        </div>
    </Main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Main from "../layouts/Main.vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useAuthStore } from "../stores/authStore.js";

const toast = useToast();
const auth = useAuthStore();

const users = ref([
    { id: 1, name: 'Alice', email: 'alice@example.com', lastMessage: 'Hello!' },
    { id: 2, name: 'Bob', email: 'bob@example.com', lastMessage: 'Do you have updates?' },
    { id: 3, name: 'Support Team', email: 'support@example.com', lastMessage: 'We are checking now.' }
]);

const selectedUser = ref(null);
const messages = ref([]);
const message = ref('');

// Filter messages shown in chat by selected user
const filteredMessages = computed(() =>
    selectedUser.value
        ? messages.value.filter(
            msg =>
                (msg.user.id === auth.user.id && msg.to === selectedUser.value.id) ||
                (msg.user.id === selectedUser.value.id && msg.to === auth.user.id)
        )
        : []
);

function selectUser(user) {
    selectedUser.value = user;
    message.value = '';
}

async function sendMessage() {
    if (!message.value.trim()) return;

    const data = {
        id: Date.now(),
        text: message.value,
        timestamp: new Date(),
        user: { id: auth.user.id, name: auth.user.name },
        to: selectedUser.value.id
    };

    // Send to backend
    try {
        await axios.post('/api/send-message', data);
        // Wait for Echo to receive and update the UI (don't push manually anymore)
        message.value = '';
        scrollToBottom();
    } catch (e) {
        toast.error('Failed to send message');
    }
}

function scrollToBottom() {
    setTimeout(() => {
        const chatBox = document.querySelector('.overflow-y-auto');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }, 0);
}

function formatTime(ts) {
    return new Date(ts).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

const getUsers = async () => {
    const { data } = await axios.get('/api/users');
    users.value = data.data;
};

onMounted(() => getUsers());

onMounted(() => {
    Echo.private('send-message').listen('ChatEvent', function (res) {
        console.log('Echo received:', res);

        messages.value.push(res.message);
        if (res.message.user.id !== auth.user.id && selectedUser.value.id === res.message.user.id) {
            toast.success(`${res.message.user.name} messaged you`);
        }

        scrollToBottom();
    });
});
</script>
