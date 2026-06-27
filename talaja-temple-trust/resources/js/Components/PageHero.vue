<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    image: { type: String, default: '/storage/hero/temple-1.jpg' },
    breadcrumb: { type: String, default: '' },
});
</script>

<template>
    <section class="relative flex h-[35vh] min-h-[260px] items-center justify-center overflow-hidden">
        <img
            :src="image"
            :alt="title"
            class="absolute inset-0 h-full w-full object-cover"
            fetchpriority="high"
            loading="eager"
        />
        <!-- Light airy overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-saffron-50/30 to-white/60"></div>
        <div class="absolute inset-0 bg-maroon-900/30"></div>

        <div class="relative z-10 px-4 text-center">
            <p v-if="breadcrumb" class="mb-2 text-xs font-medium uppercase tracking-[0.2em] text-saffron-600">{{ breadcrumb }}</p>
            <h1 class="font-serif text-3xl font-bold text-maroon-900 drop-shadow-sm md:text-5xl">{{ title }}</h1>
            <p v-if="subtitle" class="mx-auto mt-3 max-w-2xl text-base text-gray-600">{{ subtitle }}</p>
        </div>
    </section>
</template>