<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ page: Object, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head>
            <title>{{ page?.title || 'Page' }}</title>
            <meta v-if="page?.meta_description" name="description" :content="page.meta_description" />
        </Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">{{ page?.title }}</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose max-w-none" v-html="page?.content || '<p>Content coming soon.</p>'"></div>
        </section>
    </AppLayout>
</template>
