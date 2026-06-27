<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ query: String, results: Array, locale: String });
const typeColor = { News: 'bg-saffron-100 text-saffron-700', Page: 'bg-maroon-100 text-maroon-700', Gallery: 'bg-blue-100 text-blue-700', Publication: 'bg-green-100 text-green-700' };
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Search</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Search</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-12">
            <form method="get" action="/search" class="mb-8">
                <input name="q" :value="query" placeholder="Search..." class="w-full rounded-full border-gray-300 px-6 py-3 shadow" />
            </form>
            <p v-if="query" class="mb-4 text-gray-600">{{ results.length }} result(s) for "{{ query }}"</p>
            <div class="space-y-3">
                <a v-for="(r, i) in results" :key="i" :href="r.url" class="card-temple block hover:shadow-lg">
                    <span class="mb-1 inline-block rounded px-2 py-0.5 text-xs" :class="typeColor[r.type] || 'bg-gray-100'">{{ r.type }}</span>
                    <p class="font-serif text-lg text-maroon-900">{{ r.title }}</p>
                    <p v-if="r.excerpt" class="text-sm text-gray-600" v-html="r.excerpt"></p>
                </a>
            </div>
            <p v-if="query && !results.length" class="text-center text-gray-500">No results found.</p>
        </section>
    </AppLayout>
</template>
