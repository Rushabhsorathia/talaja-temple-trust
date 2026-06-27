<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head } from '@inertiajs/vue3';
import { HandHeart, Sparkles, Sun } from '@lucide/vue';

defineProps({ temple: Object, locale: String });

const values = [
    { icon: HandHeart, title: 'Devotion', desc: 'Upholding the sanctity of worship and tradition.' },
    { icon: HandHeart, title: 'Service', desc: 'Extending charitable reach to all in need.' },
    { icon: Sparkles, title: 'Transparency', desc: 'Ethical, accountable governance of every offering.' },
    { icon: Sun, title: 'Heritage', desc: 'Preserving our spiritual legacy for generations.' },
];
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>About the Trust</title></Head>
        <PageHero title="About the Trust" subtitle="A sacred legacy of devotion, service and community" image="/storage/about/temple.jpg" breadcrumb="Home · About" />

        <section class="mx-auto max-w-3xl px-4 py-16">
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900 prose-a:text-saffron-600" v-html="temple?.translation?.about_trust || '<p>Content coming soon.</p>'"></div>
        </section>

        <!-- Values -->
        <section class="bg-saffron-50 py-16">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="mb-10 text-center font-serif text-3xl font-bold text-maroon-900">Our Core Values</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="v in values" :key="v.title" class="rounded-2xl bg-white p-8 text-center shadow-md">
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
                <img src="/storage/about/lake.jpg" alt="Temple lake" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl" />
                <div>
                    <h2 class="mb-4 font-serif text-3xl font-bold text-maroon-900">A Place of Peace</h2>
                    <p class="text-gray-600">Beside the serene waters and sacred hills, the temple offers a tranquil retreat for meditation, prayer and reflection. Devotees from across the world come to seek blessings and experience the divine presence.</p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
