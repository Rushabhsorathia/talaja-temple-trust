<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ faqs: Object, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>FAQs</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Frequently Asked Questions</h1>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-16">
            <div v-for="(items, cat) in faqs" :key="cat" class="mb-8">
                <h2 v-if="cat && cat !== 'null'" class="mb-3 font-serif text-xl text-maroon-900">{{ cat }}</h2>
                <div class="space-y-3">
                    <details v-for="(f, i) in items" :key="i" class="card-temple !py-4">
                        <summary class="cursor-pointer font-medium text-maroon-900">{{ f.question }}</summary>
                        <p class="mt-2 text-sm text-gray-600" v-html="f.answer"></p>
                    </details>
                </div>
            </div>
            <p v-if="!Object.keys(faqs).length" class="text-center text-gray-500">No FAQs yet.</p>
        </section>
    </AppLayout>
</template>
