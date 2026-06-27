<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ news: Object, categories: Array, selectedCategory: String, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>News & Updates</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">News & Updates</h1>
        </section>
        <section class="mx-auto max-w-6xl px-4 py-12">
            <div class="mb-8 flex flex-wrap gap-2">
                <Link href="/news-and-updates" class="rounded-full px-4 py-1.5 text-sm" :class="!selectedCategory ? 'bg-saffron-600 text-white' : 'bg-saffron-50 text-saffron-700'">All</Link>
                <Link v-for="c in categories" :key="c" :href="`/news-and-updates?category=${c}`" class="rounded-full px-4 py-1.5 text-sm" :class="selectedCategory === c ? 'bg-saffron-600 text-white' : 'bg-saffron-50 text-saffron-700'">{{ c }}</Link>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <Link v-for="n in news.data" :key="n.id" :href="`/view-news/${n.slug}`" class="card-temple overflow-hidden !p-0 hover:shadow-lg">
                    <div class="aspect-video bg-saffron-100">
                        <img v-if="n.image_path" :src="`/storage/${n.image_path}`" :alt="n.title" class="h-full w-full object-cover" />
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-saffron-600">{{ n.published_at }}</p>
                        <h3 class="mt-1 font-serif text-lg text-maroon-900">{{ n.title }}</h3>
                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ n.excerpt }}</p>
                    </div>
                </Link>
            </div>
            <div v-if="news.links?.length > 1" class="mt-8 flex justify-center gap-1">
                <Link v-for="(link, i) in news.links" :key="i" :href="link.url || '#'" v-html="link.label" class="rounded border px-3 py-1 text-sm" :class="link.active ? 'border-saffron-600 bg-saffron-600 text-white' : 'border-gray-200'" />
            </div>
        </section>
    </AppLayout>
</template>
