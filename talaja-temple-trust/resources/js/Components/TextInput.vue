<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="block w-full rounded-lg border-gray-300 shadow-sm transition focus:border-saffron-500 focus:outline-none focus:ring-2 focus:ring-saffron-500/30"
        v-model="model"
        ref="input"
    />
</template>
