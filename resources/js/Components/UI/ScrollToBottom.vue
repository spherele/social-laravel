<template>
    <transition name="scroll-btn">
        <button
            v-if="isVisible"
            @click="scrollToBottom"
            class="fixed bottom-8 right-8 p-3 bg-gray-800 text-white rounded-full shadow-lg hover:bg-[#1f2937] transition-all duration-300 ease-in-out"
            aria-label="Scroll to bottom"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3"
                />
            </svg>
        </button>
    </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    chatContainer: {
        type: HTMLElement,
        required: true,
    },
});

const isVisible = ref(false);

const scrollToBottom = () => {
    if (props.chatContainer) {
        props.chatContainer.scrollTo({
            top: props.chatContainer.scrollHeight,
            behavior: 'smooth', // Плавная прокрутка
        });
    }
};

const checkScroll = () => {
    if (props.chatContainer) {
        const { scrollTop, scrollHeight, clientHeight } = props.chatContainer;
        isVisible.value = scrollTop + clientHeight < scrollHeight - 50; // 50 - небольшой отступ
    }
};

onMounted(() => {
    if (props.chatContainer) {
        props.chatContainer.addEventListener('scroll', checkScroll);
    }
});

onUnmounted(() => {
    if (props.chatContainer) {
        props.chatContainer.removeEventListener('scroll', checkScroll);
    }
});
</script>

<style scoped>
.scroll-btn-enter-active,
.scroll-btn-leave-active {
    transition: all 0.3s ease;
}

.scroll-btn-enter-from,
.scroll-btn-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
