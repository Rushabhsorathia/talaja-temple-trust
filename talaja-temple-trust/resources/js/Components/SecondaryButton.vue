<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-saffron-300 hover:text-saffron-600 focus:outline-none focus:ring-2 focus:ring-saffron-500 focus:ring-offset-2 disabled:opacity-60"
    >
        <slot />
    </button>
</template>
