<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ photos: Object, categories: Array, selectedCategory: String, locale: String });
const lightbox = ref(null);
const openLightbox = (src, alt) => { lightbox.value = { src, alt }; document.body.style.overflow = 'hidden'; };
const closeLightbox = () => { lightbox.value = null; document.body.style.overflow = ''; };
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Photo Gallery</title></Head>
        <PageHero title="Photo Gallery" subtitle="Glimpses of devotion, festivals and celebrations" image="/storage/hero/temple-1.jpg" breadcrumb="Home · Gallery" />

        <section class="mx-auto max-w-7xl px-4 py-12">
            <!-- Categories -->
            <div class="mb-8 flex flex-wrap justify-center gap-2">
                <Link href="/photo-gallery" class="rounded-full px-5 py-2 text-sm font-medium transition" :class="!selectedCategory ? 'bg-saffron-600 text-white shadow' : 'bg-saffron-50 text-saffron-700 hover:bg-saffron-100'">All</Link>
                <Link v-for="c in categories" :key="c" :href="`/photo-gallery?category=${c}`" class="rounded-full px-5 py-2 text-sm font-medium transition" :class="selectedCategory === c ? 'bg-saffron-600 text-white shadow' : 'bg-saffron-50 text-saffron-700 hover:bg-saffron-100'">{{ c }}</Link>
            </div>

            <!-- Masonry grid -->
            <div class="columns-2 gap-4 md:columns-3 lg:columns-4 [&>*]:mb-4">
                <div v-for="p in photos.data" :key="p.id" class="break-inside-avoid cursor-pointer overflow-hidden rounded-xl shadow-md transition hover:shadow-2xl" @click="openLightbox(`/storage/${p.image_path}`, p.alt_text || p.title)">
                    <img :src="`/storage/${p.image_path}`" :alt="p.alt_text || p.title" class="w-full transition duration-500 hover:scale-105" loading="lazy" />
                </div>
            </div>

            <p v-if="!photos.data.length" class="text-center text-gray-500">No photos yet.</p>

            <!-- Pagination -->
            <div v-if="photos.links?.length > 1" class="mt-10 flex justify-center gap-1">
                <Link v-for="(link, i) in photos.links" :key="i" :href="link.url || '#'" v-html="link.label" class="rounded-lg border px-3 py-1.5 text-sm transition" :class="link.active ? 'border-saffron-600 bg-saffron-600 text-white' : 'border-gray-200 hover:bg-saffron-50'" />
            </div>
        </section>

        <!-- Lightbox -->
        <teleport to="body">
            <div v-if="lightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4" @click="closeLightbox">
                <button class="absolute right-5 top-5 text-3xl text-white" @click="closeLightbox" aria-label="Close">×</button>
                <img :src="lightbox.src" :alt="lightbox.alt" class="max-h-[90vh] max-w-full rounded-lg shadow-2xl" />
            </div>
        </teleport>
    </AppLayout>
</template>
