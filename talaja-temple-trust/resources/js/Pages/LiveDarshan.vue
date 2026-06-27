<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ streamUrl: String, isLive: Boolean, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Live Darshan</title></Head>
        <section class="relative h-[60vh] min-h-[400px] bg-maroon-950">
            <iframe v-if="streamUrl" :src="streamUrl" class="h-full w-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            <div v-else class="flex h-full items-center justify-center bg-temple-gradient text-center text-white">
                <div>
                    <p class="font-serif text-2xl text-saffron-200">|| Jay Mataji ||</p>
                    <h1 class="mt-2 font-serif text-3xl">Live Darshan</h1>
                    <p class="mt-2 text-cream/80">Live streaming is currently offline.</p>
                </div>
            </div>
        </section>
        <section class="mx-auto max-w-3xl px-4 py-12 text-center">
            <h1 class="font-serif text-3xl text-maroon-900">Live Darshan</h1>
            <p class="mt-3 text-gray-600">
                Distance should never come in the way of devotion. Experience the divine presence of the deity
                from the comfort of your home. Darshan timings are subject to temple aarti and seva schedule.
            </p>
            <div class="mt-6 inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm" :class="isLive ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'">
                <span class="h-2 w-2 rounded-full" :class="isLive ? 'animate-pulse bg-red-500' : 'bg-gray-400'"></span>
                {{ isLive ? 'LIVE NOW' : 'Offline' }}
            </div>
        </section>
    </AppLayout>
</template>
