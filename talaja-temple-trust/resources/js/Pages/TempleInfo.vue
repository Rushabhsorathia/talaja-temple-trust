<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
import { Lamp, Flame, HandHeart, CircleDot, PartyPopper } from '@lucide/vue';

defineProps({ temple: Object, timings: Object, festivals: Array, locale: String });

const typeIcons = { darshan: Lamp, aarti: Flame, pooja: HandHeart };
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Temple Information</title></Head>
        <PageHero title="Temple Information" subtitle="Timings, schedules, festivals and how to reach us" image="/storage/hero/temple-3.jpg" breadcrumb="Home · Temple Info" />

        <section class="mx-auto max-w-6xl px-4 py-16">
            <!-- Timings -->
            <div class="mb-20 text-center">
                <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Daily Schedule</p>
                <h2 class="mb-10 font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Darshan, Aarti & Pooja Timings</h2>
                <div class="grid gap-6 text-left md:grid-cols-3">
                    <div v-for="(items, type) in timings" :key="type" class="overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md">
                        <div class="flex items-center gap-2 bg-gradient-to-r from-saffron-500 to-saffron-700 px-5 py-3 text-white">
                            <span class="text-xl">
                                <component :is="typeIcons[type] || CircleDot" :size="20" :stroke-width="2" />
                            </span>
                            <h3 class="font-serif text-lg font-semibold capitalize">{{ type }}</h3>
                        </div>
                        <table class="w-full text-sm">
                            <tbody>
                                <tr v-for="(t, i) in items" :key="i" class="border-b border-saffron-50 last:border-0">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ t.title }}</td>
                                    <td class="px-5 py-3 text-right text-gray-500">{{ t.start_time }}{{ t.end_time ? ' - ' + t.end_time : '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Festivals -->
            <div class="mb-20 text-center">
                <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Upcoming</p>
                <h2 class="mb-10 font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Festival Calendar</h2>
                <div class="grid gap-6 text-left md:grid-cols-3">
                    <div v-for="f in festivals" :key="f.title" class="group overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="aspect-video overflow-hidden bg-saffron-100">
                            <img v-if="f.image_path" :src="`/storage/${f.image_path}`" :alt="f.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                            <div v-else class="flex h-full items-center justify-center bg-gradient-to-br from-saffron-200 to-saffron-400 text-white">
                                <PartyPopper :size="40" :stroke-width="1.75" />
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-semibold text-saffron-600">{{ f.start_date }}{{ f.end_date ? ' → ' + f.end_date : '' }}</p>
                            <h3 class="mt-1 font-serif text-xl font-semibold text-maroon-900">{{ f.title }}</h3>
                            <p class="mt-2 text-sm text-gray-500">{{ f.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map -->
        <section v-if="temple?.map_embed" class="px-4 pb-16">
            <div class="mx-auto max-w-6xl overflow-hidden rounded-2xl shadow-xl">
                <iframe :src="temple.map_embed" class="h-96 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </AppLayout>
</template>
