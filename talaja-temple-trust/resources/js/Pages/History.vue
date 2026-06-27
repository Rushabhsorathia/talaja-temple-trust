<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ temple: Object, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>History</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">History</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose max-w-none" v-html="temple?.translation?.history || '<p>Content coming soon.</p>'"></div>
        </section>
    </AppLayout>
</template>
