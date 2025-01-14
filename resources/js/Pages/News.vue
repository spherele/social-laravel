<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Post from "@/Components/Posts/Index.vue";
import {ref} from "vue";
import CreatePost from "@/Components/Posts/CreatePost.vue";

const profile = ref({
    name: 'Sarah Anderson',
    role: 'Senior Software Engineer',
    avatar: 'https://masterpiecer-images.s3.yandex.net/34e974e177bb11ee9ad5ceda526c50ab:upscaled',
    about: 'Passionate software engineer with 5+ years of experience in full-stack development. Focused on creating elegant solutions to complex problems.',
    location: 'San Francisco, CA',
    email: 'sarah.anderson@example.com',
    company: 'Tech Innovations Inc',
    skills: ['React', 'TypeScript', 'Node.js', 'Python', 'AWS', 'Docker'],
    github: 'https://github.com',
    linkedin: 'https://linkedin.com',
})

const posts = ref([
    {
        id: 1,
        content: 'Hello, world!',
        image: 'https://cs13.pikabu.ru/post_img/big/2023/11/12/8/1699791312112215299.png',
        date: '11 November 2025',
        likes: 42,
        commentsCount: 7,
        watchCount: 200,
    },
    {
        id: 2,
        content: 'Some random thoughts...',
        image: 'https://cs14.pikabu.ru/post_img/big/2023/11/12/8/1699791528138162364.png',
        date: '12 November 2025',
        likes: 15,
        commentsCount: 3,
        watchCount: 200,
    },
    {
        id: 3,
        content: 'Let me share some Vue 3 tips...',
        image: 'https://masterpiecer-images.s3.yandex.net/34e974e177bb11ee9ad5ceda526c50ab:upscaled',
        date: '13 November 2025',
        likes: 30,
        commentsCount: 10,
        watchCount: 200,
    },
]);

const addPost = (newPost) => {
    posts.value.unshift(newPost);
};

</script>

<template>
    <Head title="News" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-5xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg flex flex-col gap-6 bg-grey-800">
                    <CreatePost @post-created="addPost" />

                    <Post class="p-6 md:p-8"
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                        :profile="profile"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
