<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ videos: Array, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Video Gallery</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Video Gallery</h1>
        </section>
        <section class="mx-auto max-w-6xl px-4 py-12">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="v in videos" :key="v.id" class="overflow-hidden rounded-2xl bg-white shadow-temple">
                    <div class="aspect-video">
                        <iframe :src="v.embed_url" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="p-4 font-serif text-maroon-900">{{ v.title }}</p>
                </div>
            </div>
            <p v-if="!videos.length" class="text-center text-gray-500">No videos yet.</p>
        </section>
    </AppLayout>
</template>
