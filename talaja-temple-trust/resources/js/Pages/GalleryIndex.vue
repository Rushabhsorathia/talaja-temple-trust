<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
import { Camera, Video } from '@lucide/vue';
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
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-white/90 via-white/50 to-transparent p-6">
                        <h3 class="flex items-center gap-3 font-serif text-2xl font-bold text-maroon-900">
                            <Camera :size="28" :stroke-width="2" class="text-saffron-600" />
                            Photo Gallery
                        </h3>
                    </div>
                </a>
                <a href="/video-gallery" class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <img src="/storage/gallery/g-05.jpg" alt="Videos" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-white/90 via-white/50 to-transparent p-6">
                        <h3 class="flex items-center gap-3 font-serif text-2xl font-bold text-maroon-900">
                            <Video :size="28" :stroke-width="2" class="text-saffron-600" />
                            Video Gallery
                        </h3>
                    </div>
                </a>
            </div>
        </section>
    </AppLayout>
</template>
