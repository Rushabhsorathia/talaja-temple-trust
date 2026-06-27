<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ publications: Array, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Downloads</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Downloads & Publications</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-16">
            <ul class="space-y-3">
                <li v-for="p in publications" :key="p.id" class="card-temple flex items-center justify-between">
                    <span class="font-medium text-maroon-900">📄 {{ p.title }}</span>
                    <a :href="`/storage/${p.file_path}`" target="_blank" class="text-saffron-600 hover:underline">Download</a>
                </li>
            </ul>
            <p v-if="!publications.length" class="text-center text-gray-500">No publications yet.</p>
        </section>
    </AppLayout>
</template>
