<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ news: Object, categories: Array, selectedCategory: String, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>News & Updates</title></Head>
        <PageHero title="News & Updates" subtitle="Latest happenings, events and announcements" image="/storage/news/news-1.jpg" breadcrumb="Home · News" />

        <section class="mx-auto max-w-7xl px-4 py-12">
            <div class="mb-8 flex flex-wrap justify-center gap-2">
                <Link href="/news-and-updates" class="rounded-full px-5 py-2 text-sm font-medium transition" :class="!selectedCategory ? 'bg-saffron-600 text-white shadow' : 'bg-saffron-50 text-saffron-700 hover:bg-saffron-100'">All</Link>
                <Link v-for="c in categories" :key="c" :href="`/news-and-updates?category=${c}`" class="rounded-full px-5 py-2 text-sm font-medium capitalize transition" :class="selectedCategory === c ? 'bg-saffron-600 text-white shadow' : 'bg-saffron-50 text-saffron-700 hover:bg-saffron-100'">{{ c }}</Link>
            </div>

            <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                <Link v-for="n in news.data" :key="n.id" :href="`/view-news/${n.slug}`" class="group flex flex-col overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="aspect-video overflow-hidden bg-saffron-100">
                        <img v-if="n.image_path" :src="`/storage/${n.image_path}`" :alt="n.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="mb-2 flex items-center gap-2 text-xs">
                            <span class="rounded-full bg-saffron-100 px-2.5 py-0.5 font-semibold capitalize text-saffron-700">{{ n.category }}</span>
                            <span class="text-gray-400">{{ n.published_at }}</span>
                        </div>
                        <h3 class="font-serif text-lg font-semibold leading-snug text-maroon-900 transition group-hover:text-saffron-700">{{ n.title }}</h3>
                        <p class="mt-2 line-clamp-3 flex-1 text-sm text-gray-500" v-html="n.excerpt"></p>
                        <span class="mt-4 text-sm font-medium text-saffron-600">Read more →</span>
                    </div>
                </Link>
            </div>

            <div v-if="news.links?.length > 1" class="mt-10 flex justify-center gap-1">
                <Link v-for="(link, i) in news.links" :key="i" :href="link.url || '#'" v-html="link.label" class="rounded-lg border px-3 py-1.5 text-sm transition" :class="link.active ? 'border-saffron-600 bg-saffron-600 text-white' : 'border-gray-200 hover:bg-saffron-50'" />
            </div>
        </section>
    </AppLayout>
</template>
