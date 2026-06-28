<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ temple: Object, locale: String });
const page = usePage();

const sections = computed(() => page.props.pages?.history?.sections ?? {});
const intro = computed(() => sections.value.intro || {});
const timelineSection = computed(() => sections.value.timeline || {});

const introHtml = computed(() => intro.value.content || props.temple?.translation?.history || '<p>Content coming soon.</p>');

const timeline = computed(() => {
    const items = timelineSection.value.data?.items ?? {};
    return Object.entries(items).map(([year, desc]) => ({ year, title: year, desc }));
});
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>{{ intro.title || 'History' }}</title></Head>
        <PageHero :title="intro.title || 'Our History'" :subtitle="intro.subtitle || 'A timeless journey of faith across generations'" image="/storage/hero/temple-2.jpg" breadcrumb="Home · History" />

        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900" v-html="introHtml"></div>
        </section>

        <!-- Timeline -->
        <section v-if="timeline.length" class="bg-saffron-50 py-16">
            <div class="mx-auto max-w-4xl px-4">
                <h2 class="mb-12 text-center font-serif text-3xl font-bold text-maroon-900">{{ timelineSection.title || 'Our Journey' }}</h2>
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