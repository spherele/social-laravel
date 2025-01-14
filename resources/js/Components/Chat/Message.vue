<!-- src/Components/Chat/Message.vue -->
<template>
    <div
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
                    class="max-w-[300px] h-auto rounded-lg cursor-pointer"
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
                <span v-if="message.sender === 'me'" class="text-xs text-gray-400 ml-2">✓</span>
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
</template>
