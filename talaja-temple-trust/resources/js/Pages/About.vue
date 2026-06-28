<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { HandHeart, Sparkles, Sun, Star } from '@lucide/vue';

const props = defineProps({ temple: Object, locale: String });
const page = usePage();

const sections = computed(() => page.props.pages?.about?.sections ?? {});
const intro = computed(() => sections.value.intro || {});
const valuesSection = computed(() => sections.value.values || {});
const placeSection = computed(() => sections.value.place || {});

// Admin can override icon mapping via data.icon map; falls back to lucide
// component lookup. New icons added without code change are simply ignored.
const iconMap = { hand: HandHeart, heart: HandHeart, sparkles: Sparkles, sun: Sun, star: Star };

const valueItems = computed(() => {
    const items = valuesSection.value.data?.items ?? {};
    return Object.entries(items).map(([title, desc]) => ({
        icon: iconMap[title?.toLowerCase()] || HandHeart,
        title,
        desc,
    }));
});

const introHtml = computed(() => intro.value.content || props.temple?.translation?.about_trust || '<p>Content coming soon.</p>');
const placeHtml = computed(() => placeSection.value.content || '<p>Beside the serene waters and sacred hills, the temple offers a tranquil retreat for meditation, prayer and reflection.</p>');
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>{{ intro.title || 'About the Trust' }}</title></Head>
        <PageHero :title="intro.title || 'About the Trust'" :subtitle="intro.subtitle || 'A sacred legacy of devotion, service and community'" image="/storage/about/temple.jpg" breadcrumb="Home · About" />

        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900 prose-a:text-saffron-600" v-html="introHtml"></div>
        </section>

        <!-- Values -->
        <section v-if="valueItems.length" class="bg-saffron-50 py-16">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="mb-10 text-center font-serif text-3xl font-bold text-maroon-900">{{ valuesSection.title || 'Our Core Values' }}</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="v in valueItems" :key="v.title" class="rounded-2xl bg-white p-8 text-center shadow-md">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-saffron-100 text-saffron-700">
                            <component :is="v.icon" :size="28" :stroke-width="1.75" />
                        </div>
                        <h3 class="font-serif text-lg font-semibold text-maroon-900">{{ v.title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ v.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image split -->
        <section class="mx-auto max-w-7xl px-4 py-16">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <img :src="placeSection.data?.image || '/storage/about/lake.jpg'" alt="Temple" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl" />
                <div>
                    <h2 class="mb-4 font-serif text-3xl font-bold text-maroon-900">{{ placeSection.title || 'A Place of Peace' }}</h2>
                    <div class="text-gray-600" v-html="placeHtml"></div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>