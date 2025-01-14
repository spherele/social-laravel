<template>
    <Card class="p-6">
        <div class="flex items-center mb-4 max-h-[85vh] relative">
            <button @click="goBackToChatList" class="absolute mr-2 text-gray-600 hover:text-gray-900 min-w-16">
                &larr; Back
            </button>
            <div class="flex justify-center w-full flex-col items-center">
                <h2 class="text-xl font-semibold">{{ selectedChat.name }}</h2>
                <span class="flex items-center gap-2 text-sm text-gray-600">
                    <span>{{ selectedChat.isOnline ? 'online' : 'offline' }}</span>
                    <span
                        class="inline-block w-2 h-2 rounded-full"
                        :class="selectedChat.isOnline ? 'bg-green-500' : 'bg-red-500'"
                    ></span>
                </span>
            </div>
        </div>
        <div
            ref="chatContainer"
            class="space-y-4 custom-height overflow-y-auto pb-1.5 pr-1.5 relative"
            @scroll="handleScroll"
        >
            <div
                v-for="message in messages"
                :key="message.id"
                class="flex"
                :class="{ 'justify-end': message.sender === 'me', 'justify-start': message.sender !== 'me' }"
            >
                <Avatar
                    v-if="message.sender !== 'me'"
                    class="w-8 h-8 mr-2"
                >
                    <img :src="message.avatar" :alt="message.sender" class="object-cover"/>
                </Avatar>
                <div
                    class="max-w-[70%] p-3 rounded-lg"
                    :class="{
                        'bg-[#111827] text-white': message.sender === 'me',
                        'bg-[#f0f0f0] text-gray-800': message.sender !== 'me',
                    }"
                >
                    <p v-if="message.text">{{ message.text }}</p>
                    <div v-if="message.file" class="mt-2">
                        <img
                            v-if="isImage(message.file)"
                            :src="message.file.url"
                            :alt="message.file.name"
                            class="picture-width h-auto rounded-lg cursor-pointer"
                            @click="openImageModal(message.file.url)"
                        />
                        <div v-else class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                />
                            </svg>
                            <a
                                :href="message.file.url"
                                target="_blank"
                                class="ml-2 text-blue-500 hover:underline"
                            >
                                {{ message.file.name }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-400">
                            {{ message.time }}
                            <span v-if="message.isEdited" class="text-gray-400 ml-1">(edited)</span>
                        </span>
                        <span v-if="message.sender === 'me'" class="text-xs text-gray-400 ml-2">
                            <!-- Иконка статуса прочтения -->
                            <template v-if="message.isRead">
                                <!-- Две галочки (прочитано) -->
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-check"
                                >
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-check"
                                >
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </template>
                            <template v-else>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-check"
                                >
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </template>
                        </span>
                    </div>
                    <div
                        v-if="message.sender === 'me'"
                        class="flex items-center space-x-2 mt-1"
                    >
                        <button
                            @click="editMessage(message)"
                            class="text-xs text-gray-400 hover:text-gray-600"
                        >
                            Edit
                        </button>
                    </div>
                </div>
            </div>
            <button
                v-if="isScrolledUp"
                @click="scrollToBottom"
                class="sticky bottom-4 left-10 transform -translate-x-1/2 p-2 bg-[#111827] text-white rounded-full shadow-lg hover:bg-[#232b3d] transition-colors"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-arrow-down"
                >
                    <path d="M12 5v14" />
                    <path d="m19 12-7 7-7-7" />
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="flex items-center">
                <label for="file-input" class="p-2 cursor-pointer bg-[#111827] text-white rounded-lg mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="lucide lucide-paperclip">
                        <path d="M13.234 20.252 21 12.3"/>
                        <path
                            d="m16 6-8.414 8.586a2 2 0 0 0 0 2.828 2 2 0 0 0 2.828 0l8.414-8.586a4 4 0 0 0 0-5.656 4 4 0 0 0-5.656 0l-8.415 8.585a6 6 0 1 0 8.486 8.486"/>
                    </svg>
                </label>
                <input
                    id="file-input"
                    type="file"
                    class="hidden"
                    @change="handleFileUpload"
                />
                <input
                    v-model="newMessage"
                    @keyup.enter="sendMessage"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 p-2 border-[#111827] rounded-lg"
                    style="outline: none !important; box-shadow: none !important;"
                />
                <button
                    @click="sendMessage"
                    class="ml-2 p-2 bg-[#111827] text-white rounded-lg hover:bg-[#232b3d] transition-colors"
                >
                    <template v-if="editingMessage">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-send-horizontal">
                            <path
                                d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/>
                            <path d="M6 12h16"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-send-horizontal">
                            <path
                                d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/>
                            <path d="M6 12h16"/>
                        </svg>
                    </template>
                </button>
            </div>
        </div>
    </Card>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted, watch, nextTick } from 'vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';

const props = defineProps({
    selectedChat: {
        type: Object,
        required: true,
    },
    messages: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits([
    'goBackToChatList',
    'sendMessage',
    'handleFileUpload',
    'editMessage',
    'openImageModal',
]);

const newMessage = ref('');
const editingMessage = ref(null);
const chatContainer = ref(null);
const isScrolledUp = ref(false);

const scrollToBottom = () => {
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};

const handleScroll = () => {
    if (chatContainer.value) {
        const { scrollTop, scrollHeight, clientHeight } = chatContainer.value;
        isScrolledUp.value = scrollTop + clientHeight < scrollHeight - 50;
    }
};

watch(
    () => props.messages.length,
    () => {
        nextTick(() => {
            if (!isScrolledUp.value) {
                scrollToBottom();
            }
        });
    }
);

onMounted(() => {
    scrollToBottom();
});

const sendMessage = () => {
    emit('sendMessage', newMessage.value, editingMessage.value);
    newMessage.value = '';
    editingMessage.value = null;
};

const goBackToChatList = () => {
    emit('goBackToChatList');
};

const handleFileUpload = (event) => {
    emit('handleFileUpload', event);
};

const editMessage = (message) => {
    editingMessage.value = message;
    newMessage.value = message.text;
};

const openImageModal = (imageUrl) => {
    emit('openImageModal', imageUrl);
};

const isImage = (file) => {
    return file.type.startsWith('image/');
};
</script>

<style scoped>
.custom-height {
    height: calc(85vh - 200px);
}

.picture-width {
    max-width: 300px;
    @media (max-width: 768px) {
        max-width: 100%;
    }
}

::-webkit-scrollbar {
    width: 5px;
    height: 5px;
}

::-webkit-scrollbar-track {
    background: rgba(213, 213, 213, 0.54);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb {
    background: #1f2937;
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #1f2937;
}
</style>
