<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ news: Object, related: Array, locale: String });
const formatDate = (d) => d;
</script>

<template>
    <AppLayout :locale="locale">
        <Head>
            <title>{{ news.title }}</title>
            <meta v-if="news.meta_description" name="description" :content="news.meta_description" />
        </Head>
        <article>
            <section class="relative h-[40vh] min-h-[300px] bg-saffron-200">
                <img v-if="news.image_path" :src="`/storage/${news.image_path}`" :alt="news.title" class="h-full w-full object-cover" />
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <p class="text-saffron-300">{{ news.published_at }}</p>
                    <h1 class="font-serif text-3xl font-bold md:text-4xl">{{ news.title }}</h1>
                </div>
            </section>
            <section class="mx-auto max-w-3xl px-4 py-12">
                <div class="prose max-w-none" v-html="news.content"></div>
                <Link href="/news-and-updates" class="mt-8 inline-block text-saffron-600 hover:underline">← Back to all news</Link>
            </section>
            <section v-if="related.length" class="bg-saffron-50 py-12">
                <div class="mx-auto max-w-6xl px-4">
                    <h2 class="section-title">Related</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Link v-for="r in related" :key="r.slug" :href="`/view-news/${r.slug}`" class="card-temple overflow-hidden !p-0">
                            <div class="aspect-video bg-saffron-100">
                                <img v-if="r.image_path" :src="`/storage/${r.image_path}`" class="h-full w-full object-cover" />
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-saffron-600">{{ r.published_at }}</p>
                                <h3 class="font-serif text-maroon-900">{{ r.title }}</h3>
                            </div>
                        </Link>
                    </div>
                </div>
            </section>
        </article>
    </AppLayout>
</template>
