<script setup>
import Avatar from '@/Components/UI/Avatar.vue';
import Card from '@/Components/UI/Card.vue';
import Heart from '@/Components/UI/Icons/Heart.vue';
import Comment from '@/Components/UI/Icons/Comment.vue';
import Eye from "@/Components/UI/Icons/Eye.vue";
import { ref } from 'vue';
import ImageModal from './ImageModal.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    profile: {
        type: Object,
        required: true,
    },
    imageMaxWidth: {
        type: String,
        default: '800px',
    },
    imageMaxHeight: {
        type: String,
        default: '600px',
    },
});

const isImageModalOpen = ref(false);
const selectedImage = ref('');

const likedPosts = ref([]);

const comments = ref([]);
const newComment = ref('');

const toggleLike = (postId) => {
    if (likedPosts.value.includes(postId)) {
        likedPosts.value = likedPosts.value.filter(id => id !== postId);
    } else {
        likedPosts.value.push(postId);
    }
};

const isLiked = (postId) => likedPosts.value.includes(postId);

const addComment = () => {
    if (newComment.value.trim()) {
        comments.value.push({
            id: Date.now(),
            author: props.profile.name,
            avatar: props.profile.avatar,
            text: newComment.value.trim(),
            date: new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }),
        });

        props.post.commentsCount += 1;
        newComment.value = '';
    }
};


const openImageModal = (imageUrl) => {
    selectedImage.value = imageUrl;
    isImageModalOpen.value = true;
};

const closeImageModal = () => {
    isImageModalOpen.value = false;
    selectedImage.value = '';
};
</script>

<template>
    <Card class="bg-gray-800 p-4 shadow-sm">
        <div class="flex items-center mb-4">
            <Avatar class="w-14 h-14 mr-3">
                <img :src="profile.avatar" :alt="profile.name" class="object-cover" />
            </Avatar>
            <div class="flex-1">
                <div class="flex gap-1 flex-col items-start">
                    <span class="font-semibold text-md">{{ profile.name }}</span>
                    <span class="text-sm">{{ post.date }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden mb-4 flex justify-center" v-if="post.image">
            <img
                :src="post.image"
                alt=""
                class="object-cover rounded cursor-pointer"
                :style="{
                    maxWidth: '100%',
                    maxHeight: '600px',
                    height: 'auto',
                    objectFit: 'cover',
                    borderRadius: '14px',
                }"
                @click="(event) => { event.preventDefault(); openImageModal(post.image); }"
            />
        </div>

        <p class="text-gray-900 whitespace-pre-line mb-4 leading-relaxed">
            {{ post.content }}
        </p>

        <div class="flex items-center justify-between text-sm text-gray-400">
            <div class="flex items-center gap-4">
                <!-- Лайк -->
                <button @click="toggleLike(post.id)" class="inline-flex items-center gap-1">
                    <Heart :class="{ 'text-red-500': isLiked(post.id) }" />
                    <span :class="{ 'text-red-500': isLiked(post.id) }">
                        {{ isLiked(post.id) ? post.likes + 1 : post.likes }}
                    </span>
                </button>
                <!-- Комментарии -->
                <span class="inline-flex items-center gap-1"><Comment />{{ post.commentsCount }}</span>
            </div>
            <!-- Просмотры -->
            <span class="flex gap-1 items-center"><Eye />{{ post.watchCount }}</span>
        </div>

        <!-- Поле для добавления комментария -->
        <div class="mt-4">
            <TextArea
                v-model="newComment"
                placeholder="Write a comment..."
                :rows="2"
                :required="true"
            />
            <PrimaryButton
                @click="addComment"
            >
                Add Comment
            </PrimaryButton>
        </div>

        <!-- Список комментариев -->
        <div class="mt-4 space-y-4">
            <div v-for="comment in comments" :key="comment.id" class="flex items-start gap-3 p-3 bg-[#3741510d] rounded-lg shadow-sm">
                <Avatar class="w-8 h-8">
                    <img :src="comment.avatar" :alt="comment.author" class="object-cover" />
                </Avatar>
                <div>
                    <div class="flex gap-1">
                        <div class="text-sm font-semibold">{{ comment.author }}</div>
                        <div class="text-sm text-gray-600">{{ comment.date }}</div>
                    </div>
                    <p class="text-sm text-gray-900">{{ comment.text }}</p>
                </div>
            </div>
        </div>
    </Card>

    <ImageModal
        :isImageModalOpen="isImageModalOpen"
        :selectedImage="selectedImage"
        @close="closeImageModal"
        style="margin-top: unset"
    />

</template>
