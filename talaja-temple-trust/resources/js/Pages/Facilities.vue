<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Hotel, House, Utensils, Flame, HeartPulse, TreePine } from '@lucide/vue';

const props = defineProps({ page: Object, locale: String });

const inertiaPage = usePage();
const iconMap = { bed: Hotel, home: House, soup: Utensils, flame: Flame, cross: HeartPulse, trees: TreePine };

const sections = computed(() => inertiaPage.props.pages?.facilities?.sections ?? {});
const intro = computed(() => sections.value.intro || {});
const cta = computed(() => sections.value.cta || {});

const facilities = computed(() => (inertiaPage.props.facilities || []).map((f) => ({
    img: f.image,
    icon: iconMap[f.icon] || Hotel,
    title: f.title,
    desc: f.desc,
})));

const introHtml = computed(() => intro.value.content || props.page?.content || '<p>The trust offers a wide range of facilities and community services for devotees and visitors.</p>');
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>{{ intro.title || 'Facilities & Offerings' }}</title></Head>
        <PageHero :title="intro.title || 'Facilities & Offerings'" :subtitle="intro.subtitle || 'Serving devotees with comfort, care and devotion'" image="/storage/facilities/dharamshala.jpg" breadcrumb="Home · Facilities" />

        <section class="mx-auto max-w-3xl px-4 py-12">
            <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-maroon-900" v-html="introHtml"></div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-20">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="f in facilities" :key="f.title" class="group overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="relative aspect-video overflow-hidden">
                        <img :src="f.img" :alt="f.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                        <div class="absolute right-3 top-3 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-saffron-700 shadow">
                            <component :is="f.icon" :size="22" :stroke-width="2" />
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-serif text-xl font-semibold text-maroon-900">{{ f.title }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking CTA -->
        <section v-if="cta.title" class="bg-gradient-to-r from-cream-100 to-cream-200 py-14">
            <div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center text-maroon-900">
                <h2 class="mb-3 font-serif text-3xl font-bold">{{ cta.title }}</h2>
                <p v-if="cta.subtitle" class="mb-8 text-gray-700">{{ cta.subtitle }}</p>
                <a v-if="cta.data?.cta_href" :href="cta.data.cta_href" class="rounded-full bg-saffron-600 px-8 py-3.5 font-semibold text-white shadow-xl transition hover:scale-105">{{ cta.data.cta_label || 'Learn more' }}</a>
            </div>
        </section>
    </AppLayout>
</template>
