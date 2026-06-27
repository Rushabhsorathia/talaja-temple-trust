<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
    banners: { type: Array, default: () => [] },
    latestNews: { type: Array, default: () => [] },
    galleryPreview: { type: Array, default: () => [] },
    trustees: { type: Array, default: () => [] },
    temple: { type: Object, default: null },
    locale: String,
});

// Hero slider
const slides = [
    { img: '/storage/hero/temple-1.jpg', title: 'Talaja Temple Trust', sub: 'A sacred abode of devotion and service', tag: '|| Jay Mataji ||' },
    { img: '/storage/hero/temple-2.jpg', title: 'Connect With the Divine', sub: 'Live darshan, donations & blessings — anytime, anywhere', tag: '|| Om Namah Shivay ||' },
    { img: '/storage/hero/temple-3.jpg', title: 'A Legacy of Faith', sub: 'Serving devotees with devotion for generations', tag: '|| Har Har Mahadev ||' },
];
const current = ref(0);
let timer;
const next = () => { current.value = (current.value + 1) % slides.length; };
const goTo = (i) => { current.value = i; reset(); };
let interval;
const reset = () => { clearInterval(interval); interval = setInterval(next, 5000); };
onMounted(() => { reset(); });
onUnmounted(() => clearInterval(interval));

const services = [
    { icon: '🛕', title: 'Live Darshan', desc: 'Experience divine darshan from anywhere in the world.', href: '/live-darshan', live: true },
    { icon: '🤲', title: 'Donate', desc: 'Support the temple with secure online donations (80G eligible).', href: '/donate' },
    { icon: '🛏️', title: 'Bookings', desc: 'Reserve rooms & halls for your stay and events.', href: '/bookings' },
    { icon: '🛍️', title: 'Shop', desc: 'Prasad, books and souvenirs delivered to your home.', href: '/shop' },
];

const stats = [
    { value: '5L+', label: 'Devotees Served' },
    { value: '100+', label: 'Years of Legacy' },
    { value: '500+', label: 'Daily Annaseva' },
    { value: '24/7', label: 'Live Darshan' },
];
</script>

<template>
    <AppLayout :locale="locale">
        <!-- Hero Slider -->
        <section class="relative h-[80vh] min-h-[500px] overflow-hidden">
            <transition-group tag="div" name="fade">
                <div v-for="(slide, i) in slides" v-show="current === i" :key="i" class="absolute inset-0">
                    <img :src="slide.img" :alt="slide.title" class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-maroon-950/70"></div>
                </div>
            </transition-group>

            <div class="relative z-10 flex h-full items-center justify-center px-4">
                <div class="max-w-3xl text-center text-white">
                    <transition name="fade" mode="out-in">
                        <div :key="current">
                            <p class="mb-3 font-serif text-xl tracking-wide text-saffron-300">{{ slides[current].tag }}</p>
                            <h1 class="mb-4 font-serif text-4xl font-bold drop-shadow-lg md:text-6xl">{{ slides[current].title }}</h1>
                            <p class="mx-auto mb-8 max-w-xl text-lg text-cream/90 md:text-xl">{{ slides[current].sub }}</p>
                        </div>
                    </transition>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="/donate" class="rounded-full bg-gradient-to-r from-saffron-500 to-saffron-700 px-8 py-3.5 font-semibold text-white shadow-xl transition hover:scale-105">Donate Now</a>
                        <a href="/live-darshan" class="flex items-center gap-2 rounded-full border-2 border-white/70 px-8 py-3.5 font-semibold text-white backdrop-blur transition hover:bg-white hover:text-maroon-900">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span> Live Darshan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dots -->
            <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                <button v-for="(slide, i) in slides" :key="i" @click="goTo(i)" :aria-label="`Slide ${i+1}`" class="h-2.5 rounded-full transition-all" :class="current === i ? 'w-8 bg-saffron-500' : 'w-2.5 bg-white/50'"></button>
            </div>
        </section>

        <!-- Stats bar -->
        <section class="bg-maroon-900 py-8">
            <div class="mx-auto grid max-w-6xl grid-cols-2 gap-6 px-4 md:grid-cols-4">
                <div v-for="s in stats" :key="s.label" class="text-center">
                    <p class="font-serif text-3xl font-bold text-saffron-300 md:text-4xl">{{ s.value }}</p>
                    <p class="text-xs text-cream/70 md:text-sm">{{ s.label }}</p>
                </div>
            </div>
        </section>

        <!-- About teaser -->
        <section class="mx-auto max-w-7xl px-4 py-20">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Welcome to</p>
                    <h2 class="mb-5 font-serif text-3xl font-bold text-maroon-900 md:text-4xl">A Legacy of Devotion and Service</h2>
                    <div class="space-y-4 text-gray-600" v-html="temple?.translation?.about_trust || '<p>Nestled in the serene landscape of Talaja, our temple has been a beacon of faith for generations. The trust is dedicated to preserving sanctity, serving devotees, and extending charitable reach.</p>'"></div>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="/about-us" class="rounded-full bg-gradient-to-r from-saffron-500 to-saffron-700 px-6 py-3 font-semibold text-white shadow-lg transition hover:scale-105">Read More</a>
                        <a href="/trustees" class="rounded-full border-2 border-maroon-200 px-6 py-3 font-semibold text-maroon-800 transition hover:bg-maroon-50">Our Trustees</a>
                    </div>
                </div>
                <div class="relative">
                    <img src="/storage/about/temple.jpg" alt="Temple" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-2xl" />
                    <img src="/storage/about/lake.jpg" alt="Temple lake" class="absolute -bottom-8 -left-8 hidden h-48 w-48 rounded-2xl border-4 border-white object-cover shadow-xl md:block" />
                    <div class="absolute -right-4 -top-4 rounded-2xl bg-saffron-600 px-6 py-4 text-center text-white shadow-xl">
                        <p class="font-serif text-2xl font-bold">100+</p>
                        <p class="text-xs">Years</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="bg-gradient-to-b from-saffron-50 to-cream py-20">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mb-12 text-center">
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Connect with the Divine</p>
                    <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Online Services & Offerings</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-gray-500">Stay connected with your faith, no matter where you are.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <a v-for="svc in services" :key="svc.title" :href="svc.href" class="group rounded-2xl border border-saffron-100 bg-white p-8 text-center shadow-md transition hover:-translate-y-2 hover:shadow-2xl">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-saffron-100 to-saffron-200 text-3xl transition group-hover:scale-110">{{ svc.icon }}</div>
                        <h3 class="mb-2 font-serif text-xl font-semibold text-maroon-900">{{ svc.title }}</h3>
                        <p class="text-sm text-gray-500">{{ svc.desc }}</p>
                        <span class="mt-4 inline-block text-sm font-medium text-saffron-600 transition group-hover:underline">Learn more →</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- News -->
        <section v-if="latestNews.length" class="mx-auto max-w-7xl px-4 py-20">
            <div class="mb-12 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Stay Updated</p>
                    <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">News & Updates</h2>
                </div>
                <a href="/news-and-updates" class="rounded-full border-2 border-saffron-200 px-5 py-2 text-sm font-semibold text-saffron-700 transition hover:bg-saffron-50">View All →</a>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <a v-for="n in latestNews" :key="n.id" :href="`/view-news/${n.slug}`" class="group overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="aspect-video overflow-hidden bg-saffron-100">
                        <img v-if="n.image_path" :src="`/storage/${n.image_path}`" :alt="n.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-medium text-saffron-600">{{ n.published_at }}</p>
                        <h3 class="mt-1 font-serif text-lg font-semibold leading-snug text-maroon-900 group-hover:text-saffron-700">{{ n.title }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-gray-500" v-html="n.excerpt"></p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Trustees -->
        <section v-if="trustees.length" class="bg-maroon-950 py-20">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mb-12 text-center">
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-400">Our Guiding Lights</p>
                    <h2 class="font-serif text-3xl font-bold text-saffron-300 md:text-4xl">Board of Trustees</h2>
                </div>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="t in trustees" :key="t.name" class="text-center text-cream/90">
                        <div class="mx-auto mb-4 h-28 w-28 overflow-hidden rounded-full ring-4 ring-saffron-500/30">
                            <img v-if="t.photo_path" :src="`/storage/${t.photo_path}`" :alt="t.name" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full w-full items-center justify-center bg-saffron-600 text-3xl font-bold">{{ t.name?.charAt(0) }}</div>
                        </div>
                        <p class="font-serif text-lg font-semibold">{{ t.name }}</p>
                        <p class="text-sm text-saffron-400">{{ t.designation }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="bg-gradient-to-r from-saffron-500 to-saffron-700 py-16">
            <div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center text-white">
                <h2 class="mb-3 font-serif text-3xl font-bold md:text-4xl">Begin Your Spiritual Journey</h2>
                <p class="mb-8 text-cream/90">Join thousands of devotees. Donate, book your stay, or simply receive blessings.</p>
                <a href="/donate" class="rounded-full bg-white px-8 py-3.5 font-bold text-saffron-700 shadow-xl transition hover:scale-105">Donate Now</a>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 1s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
