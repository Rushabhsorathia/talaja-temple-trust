<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ trustees: Array, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Trustees</title></Head>
        <PageHero title="Board of Trustees" subtitle="Dedicated stewards of faith and service" image="/storage/hero/temple-3.jpg" breadcrumb="Home · Trustees" />

        <section class="mx-auto max-w-6xl px-4 py-16">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="t in trustees" :key="t.name" class="overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative aspect-[4/3] overflow-hidden bg-saffron-100">
                        <img v-if="t.photo_path" :src="`/storage/${t.photo_path}`" :alt="t.name" class="h-full w-full object-cover" />
                        <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-saffron-200 to-saffron-400 text-5xl font-bold text-white">{{ t.name?.charAt(0) }}</div>
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-maroon-950/80 to-transparent p-4">
                            <h3 class="font-serif text-xl font-bold text-white">{{ t.name }}</h3>
                            <p class="text-sm text-saffron-300">{{ t.designation }}</p>
                        </div>
                    </div>
                    <p class="p-5 text-sm leading-relaxed text-gray-600" v-if="t.bio">{{ t.bio }}</p>
                </div>
            </div>
            <p v-if="!trustees.length" class="text-center text-gray-500">No trustees listed yet.</p>
        </section>
    </AppLayout>
</template>
