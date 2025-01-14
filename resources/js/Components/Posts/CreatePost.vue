<template>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Create a New Post</h2>
        <form @submit.prevent="submitPost">
            <!-- Поле для текста поста -->
            <TextArea
                v-model="postContent"
                placeholder="What's on your mind?"
                :rows="4"
                :required="true"
            />

            <div class="mb-4">
                <div class="flex gap-2">
                    <PrimaryButton
                        type="submit"
                    >
                        Post
                    </PrimaryButton>

                    <input
                        type="file"
                        @change="handleImageUpload"
                        class="block w-full text-sm text-gray-500
                           file:inline-flex file:items-center file:px-4 file:py-2
                           file:bg-gray-800 file:border file:border-transparent
                           file:rounded-md file:font-semibold file:text-xs file:text-white
                           file:uppercase file:tracking-widest
                           hover:file:bg-gray-700 focus:file:bg-gray-700 active:file:bg-gray-900
                           focus:file:outline-none focus:file:ring-2 focus:file:ring-indigo-500
                           focus:file:ring-offset-2 file:transition file:ease-in-out file:duration-150"
                        accept="image/*"
                    />
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";

const postContent = ref('');
const postImage = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        postImage.value = file;
    }
};


const submitPost = () => {
    const newPost = {
        id: Date.now(),
        content: postContent.value,
        image: postImage.value ? URL.createObjectURL(postImage.value) : null,
        date: new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }),
        likes: 0,
        commentsCount: 0,
        watchCount: 0,
    };

    emit('post-created', newPost);

    postContent.value = '';
    postImage.value = null;
};

const emit = defineEmits(['post-created']);
</script>
