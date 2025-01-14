<script setup>
const props = defineProps({
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <!-- Блок-обёртка для аватара -->
    <div :class="[
            'inline-block rounded-full bg-gray-200 overflow-hidden',
            props.class
        ]"
    >
        <slot/>
    </div>
</template>
