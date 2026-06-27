<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    image: { type: String, default: '/storage/hero/temple-1.jpg' },
    breadcrumb: { type: String, default: '' },
});
</script>

<template>
    <section class="relative flex h-[45vh] min-h-[300px] items-center justify-center overflow-hidden">
        <img :src="image" :alt="title" class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-maroon-950/70 to-maroon-950/80"></div>
        <div class="relative z-10 px-4 text-center text-white">
            <p v-if="breadcrumb" class="mb-2 text-xs tracking-widest text-saffron-300 uppercase">{{ breadcrumb }}</p>
            <h1 class="font-serif text-3xl font-bold drop-shadow-lg md:text-5xl">{{ title }}</h1>
            <p v-if="subtitle" class="mx-auto mt-3 max-w-2xl text-cream/80">{{ subtitle }}</p>
        </div>
    </section>
</template>
