<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ temple: Object, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head>
            <title>About the Trust</title>
            <meta name="description" content="About Talaja Temple Trust - a legacy of devotion and service." />
        </Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">About the Trust</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose max-w-none" v-html="temple?.translation?.about_trust || '<p>Content coming soon.</p>'"></div>
        </section>
    </AppLayout>
</template>
