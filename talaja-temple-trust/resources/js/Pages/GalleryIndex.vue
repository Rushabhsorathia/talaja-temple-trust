<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Gallery</title></Head>
        <PageHero title="Gallery" subtitle="Explore moments of devotion through photos and videos" image="/storage/gallery/g-01.jpg" breadcrumb="Home · Gallery" />
        <section class="mx-auto max-w-4xl px-4 py-16">
            <div class="grid gap-8 sm:grid-cols-2">
                <a href="/photo-gallery" class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <img src="/storage/gallery/g-02.jpg" alt="Photos" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-maroon-950/80 to-transparent p-6">
                        <h3 class="font-serif text-2xl font-bold text-white">📷 Photo Gallery</h3>
                    </div>
                </a>
                <a href="/video-gallery" class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <img src="/storage/gallery/g-05.jpg" alt="Videos" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-maroon-950/80 to-transparent p-6">
                        <h3 class="font-serif text-2xl font-bold text-white">🎥 Video Gallery</h3>
                    </div>
                </a>
            </div>
        </section>
    </AppLayout>
</template>
