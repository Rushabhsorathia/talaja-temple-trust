<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ trustees: Array, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Trustees</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Trustees</h1>
        </section>
        <section class="mx-auto max-w-6xl px-4 py-16">
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3">
                <div v-for="t in trustees" :key="t.name" class="card-temple text-center">
                    <img v-if="t.photo_path" :src="`/storage/${t.photo_path}`" :alt="t.name" class="mx-auto mb-4 h-32 w-32 rounded-full object-cover" />
                    <div v-else class="mx-auto mb-4 h-32 w-32 rounded-full bg-saffron-100"></div>
                    <h3 class="font-serif text-xl text-maroon-900">{{ t.name }}</h3>
                    <p class="text-saffron-600">{{ t.designation }}</p>
                    <p class="mt-2 text-sm text-gray-600" v-if="t.bio">{{ t.bio }}</p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
