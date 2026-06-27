<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ photos: Object, categories: Array, selectedCategory: String, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Photo Gallery</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Photo Gallery</h1>
        </section>
        <section class="mx-auto max-w-6xl px-4 py-12">
            <div class="mb-8 flex flex-wrap gap-2">
                <Link href="/photo-gallery" class="rounded-full px-4 py-1.5 text-sm" :class="!selectedCategory ? 'bg-saffron-600 text-white' : 'bg-saffron-50 text-saffron-700'">All</Link>
                <Link v-for="c in categories" :key="c" :href="`/photo-gallery?category=${c}`" class="rounded-full px-4 py-1.5 text-sm" :class="selectedCategory === c ? 'bg-saffron-600 text-white' : 'bg-saffron-50 text-saffron-700'">{{ c }}</Link>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div v-for="p in photos.data" :key="p.id" class="aspect-square overflow-hidden rounded-xl bg-saffron-100">
                    <img v-if="p.image_path" :src="`/storage/${p.image_path}`" :alt="p.alt_text || p.title" class="h-full w-full object-cover transition hover:scale-105" />
                </div>
            </div>
            <div v-if="photos.links?.length > 1" class="mt-8 flex justify-center gap-1">
                <Link v-for="(link, i) in photos.links" :key="i" :href="link.url || '#'" v-html="link.label" class="rounded border px-3 py-1 text-sm" :class="link.active ? 'border-saffron-600 bg-saffron-600 text-white' : 'border-gray-200'" />
            </div>
        </section>
    </AppLayout>
</template>
