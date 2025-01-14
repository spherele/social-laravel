<template>
    <transition name="modal">
        <div
            v-if="isCreateChatModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h2 class="text-xl font-semibold mb-4">Create Chat</h2>
                <input
                    v-model="newChatName"
                    type="text"
                    placeholder="Chat name"
                    class="w-full p-2 border border-gray-200 rounded-lg mb-4"
                />
                <input
                    v-model="newChatAvatar"
                    type="text"
                    placeholder="Avatar URL (optional)"
                    class="w-full p-2 border border-gray-200 rounded-lg mb-4"
                />
                <div class="flex justify-end">
                    <button
                        @click="closeCreateChatModal"
                        class="p-2 text-gray-600 hover:text-gray-900"
                    >
                        Cancel
                    </button>
                    <button
                        @click="createChat"
                        class="p-2 bg-[#111827] text-white rounded-lg hover:bg-[#232b3d] ml-2"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue';

const props = defineProps({
    isCreateChatModalOpen: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['closeCreateChatModal', 'createChat']);

const newChatName = ref('');
const newChatAvatar = ref('');

const closeCreateChatModal = () => {
    emit('closeCreateChatModal');
};

const createChat = () => {
    emit('createChat', newChatName.value, newChatAvatar.value);
    newChatName.value = '';
    newChatAvatar.value = '';
};
</script>
