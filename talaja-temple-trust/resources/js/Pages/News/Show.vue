<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ news: Object, related: Array, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head>
            <title>{{ news.title }}</title>
            <meta v-if="news.meta_description" name="description" :content="news.meta_description" />
        </Head>

        <article>
            <!-- Hero -->
            <section class="relative h-[50vh] min-h-[320px]">
                <img v-if="news.image_path" :src="`/storage/${news.image_path}`" :alt="news.title" class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-maroon-950 via-maroon-950/60 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-6 md:p-12">
                    <div class="mx-auto max-w-3xl">
                        <div class="mb-3 flex items-center gap-2 text-xs">
                            <span class="rounded-full bg-saffron-600 px-2.5 py-0.5 font-semibold capitalize text-white">{{ news.category }}</span>
                            <span class="text-cream/80">{{ news.published_at }}</span>
                        </div>
                        <h1 class="font-serif text-2xl font-bold text-white drop-shadow-lg md:text-4xl">{{ news.title }}</h1>
                    </div>
                </div>
            </section>

            <!-- Content -->
            <section class="mx-auto max-w-3xl px-4 py-12">
                <Link href="/news-and-updates" class="mb-6 inline-flex items-center gap-1 text-sm font-medium text-saffron-600 hover:underline">← Back to all news</Link>
                <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900 prose-img:rounded-xl" v-html="news.content"></div>

                <div v-if="news.tags?.length" class="mt-8 flex flex-wrap gap-2">
                    <span v-for="tag in news.tags" :key="tag" class="rounded-full bg-saffron-50 px-3 py-1 text-xs text-saffron-700">#{{ tag }}</span>
                </div>
            </section>

            <!-- Related -->
            <section v-if="related.length" class="bg-saffron-50 py-14">
                <div class="mx-auto max-w-6xl px-4">
                    <h2 class="mb-8 text-center font-serif text-3xl font-bold text-maroon-900">Related Updates</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        <Link v-for="r in related" :key="r.slug" :href="`/view-news/${r.slug}`" class="group overflow-hidden rounded-2xl bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="aspect-video overflow-hidden bg-saffron-100">
                                <img v-if="r.image_path" :src="`/storage/${r.image_path}`" :alt="r.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-saffron-600">{{ r.published_at }}</p>
                                <h3 class="mt-1 font-serif text-lg font-semibold text-maroon-900">{{ r.title }}</h3>
                            </div>
                        </Link>
                    </div>
                </div>
            </section>
        </article>
    </AppLayout>
</template>
