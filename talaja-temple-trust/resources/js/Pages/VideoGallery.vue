<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ videos: Array, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Video Gallery</title></Head>
        <PageHero title="Video Gallery" subtitle="Relive the divine moments of aarti, festivals and events" image="/storage/hero/temple-2.jpg" breadcrumb="Home · Videos" />
        <section class="mx-auto max-w-7xl px-4 py-12">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="v in videos" :key="v.id" class="overflow-hidden rounded-2xl bg-white shadow-md transition hover:shadow-2xl">
                    <div class="aspect-video">
                        <iframe :src="v.embed_url" class="h-full w-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                    <p class="p-4 font-serif text-lg font-semibold text-maroon-900">{{ v.title }}</p>
                </div>
            </div>
            <p v-if="!videos.length" class="text-center text-gray-500">No videos yet.</p>
        </section>
    </AppLayout>
</template>
