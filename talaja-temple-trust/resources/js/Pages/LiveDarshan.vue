<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ streamUrl: String, isLive: Boolean, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Live Darshan</title></Head>
        <PageHero title="Live Darshan" subtitle="Experience the divine presence from anywhere in the world" image="/storage/hero/temple-1.jpg" breadcrumb="Home · Live Darshan" />

        <!-- Stream -->
        <section class="bg-maroon-950 py-10">
            <div class="mx-auto max-w-5xl px-4">
                <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                    <div class="absolute left-4 top-4 z-10 flex items-center gap-2 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-lg">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-white"></span>
                        {{ isLive ? 'LIVE NOW' : 'OFFLINE' }}
                    </div>
                    <iframe v-if="streamUrl" :src="streamUrl" class="aspect-video w-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <div v-else class="flex aspect-video items-center justify-center bg-gradient-to-br from-saffron-700 to-maroon-900 text-center text-white">
                        <div>
                            <p class="font-serif text-2xl text-saffron-300">|| Jay Mataji ||</p>
                            <p class="mt-2 text-cream/80">Live streaming is currently offline.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-3xl px-4 py-16 text-center">
            <h2 class="font-serif text-3xl font-bold text-maroon-900">Darshan, Anytime, Anywhere</h2>
            <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                Distance should never come in the way of devotion. Whether near or far, stay connected to the temple and be part of every aarti and special event as it happens. Darshan timings follow the temple's aarti and seva schedule.
            </p>
            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-saffron-50 p-6">
                    <p class="font-serif text-2xl font-bold text-saffron-600">05:00</p>
                    <p class="text-sm text-gray-500">Mangla Aarti</p>
                </div>
                <div class="rounded-2xl bg-saffron-50 p-6">
                    <p class="font-serif text-2xl font-bold text-saffron-600">19:00</p>
                    <p class="text-sm text-gray-500">Sandhya Aarti</p>
                </div>
                <div class="rounded-2xl bg-saffron-50 p-6">
                    <p class="font-serif text-2xl font-bold text-saffron-600">20:30</p>
                    <p class="text-sm text-gray-500">Shayan Aarti</p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
