<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ temple: Object, locale: String });

const timeline = [
    { year: 'Ancient Era', title: 'Sacred Origins', desc: 'The temple site has been a place of worship for centuries, revered by sages and devotees alike.' },
    { year: 'Royal Patronage', title: 'Royal Patronage', desc: 'Successive rulers expanded the temple, building the grand structure that stands today.' },
    { year: 'Trust Formation', title: 'Trust Established', desc: 'A formal trust was constituted to ensure transparent governance and sustained service.' },
    { year: 'Modern Era', title: 'Digital Devotion', desc: 'The temple embraces technology — online darshan, donations and global outreach.' },
];
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>History</title></Head>
        <PageHero title="Our History" subtitle="A timeless journey of faith across generations" image="/storage/hero/temple-2.jpg" breadcrumb="Home · History" />

        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900" v-html="temple?.translation?.history || '<p>Content coming soon.</p>'"></div>
        </section>

        <!-- Timeline -->
        <section class="bg-saffron-50 py-16">
            <div class="mx-auto max-w-4xl px-4">
                <h2 class="mb-12 text-center font-serif text-3xl font-bold text-maroon-900">Our Journey</h2>
                <div class="relative">
                    <div class="absolute left-4 top-0 h-full w-0.5 bg-saffron-200 md:left-1/2"></div>
                    <div v-for="(t, i) in timeline" :key="i" class="relative mb-10 flex md:items-center" :class="i % 2 === 0 ? 'md:flex-row-reverse' : ''">
                        <div class="ml-12 md:ml-0 md:w-1/2" :class="i % 2 === 0 ? 'md:pl-12' : 'md:pr-12 md:text-right'">
                            <div class="rounded-2xl bg-white p-6 shadow-md">
                                <span class="inline-block rounded-full bg-saffron-100 px-3 py-1 text-xs font-semibold text-saffron-700">{{ t.year }}</span>
                                <h3 class="mt-2 font-serif text-xl font-semibold text-maroon-900">{{ t.title }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t.desc }}</p>
                            </div>
                        </div>
                        <div class="absolute left-4 top-4 z-10 flex h-3 w-3 -translate-x-1/2 items-center justify-center rounded-full bg-saffron-600 ring-4 ring-saffron-100 md:left-1/2"></div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
