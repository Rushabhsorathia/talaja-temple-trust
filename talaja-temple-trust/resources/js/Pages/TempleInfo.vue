<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ temple: Object, timings: Object, festivals: Array, locale: String });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Temple Info</title></Head>
        <section class="bg-temple-gradient py-20 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Temple Information</h1>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-16">
            <!-- Timings -->
            <h2 class="section-title">Timings</h2>
            <div class="mb-16 grid gap-6 md:grid-cols-3">
                <div v-for="(items, type) in timings" :key="type" class="card-temple">
                    <h3 class="mb-3 font-serif text-xl capitalize text-maroon-900">{{ type }}</h3>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr v-for="(t, i) in items" :key="i" class="border-b border-saffron-50">
                                <td class="py-2 font-medium">{{ t.title }}</td>
                                <td class="py-2 text-right text-gray-600">{{ t.start_time }}{{ t.end_time ? ' - ' + t.end_time : '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Festivals -->
            <h2 class="section-title">Festival Calendar</h2>
            <div class="grid gap-6 md:grid-cols-3">
                <div v-for="f in festivals" :key="f.title" class="card-temple">
                    <h3 class="font-serif text-lg text-maroon-900">{{ f.title }}</h3>
                    <p class="text-xs text-saffron-600">{{ f.start_date }}{{ f.end_date ? ' to ' + f.end_date : '' }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ f.description }}</p>
                </div>
            </div>
        </section>

        <!-- Map -->
        <section v-if="temple?.map_embed" class="px-4 pb-16">
            <div class="mx-auto max-w-6xl overflow-hidden rounded-2xl">
                <iframe :src="temple.map_embed" class="h-80 w-full border-0" loading="lazy"></iframe>
            </div>
        </section>
    </AppLayout>
</template>
