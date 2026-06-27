<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Gallery</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Gallery</h1>
        </section>
        <section class="mx-auto max-w-4xl px-4 py-16 text-center">
            <div class="grid gap-6 sm:grid-cols-2">
                <a href="/photo-gallery" class="card-temple text-xl font-serif text-maroon-900 hover:shadow-lg">📷 Photo Gallery</a>
                <a href="/video-gallery" class="card-temple text-xl font-serif text-maroon-900 hover:shadow-lg">🎥 Video Gallery</a>
            </div>
        </section>
    </AppLayout>
</template>
