<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Video, HandHeart, Bed, ShoppingBag, Users, Clock, Soup, Wifi } from '@lucide/vue';

const props = defineProps({
    banners: { type: Array, default: () => [] },
    latestNews: { type: Array, default: () => [] },
    galleryPreview: { type: Array, default: () => [] },
    trustees: { type: Array, default: () => [] },
    temple: { type: Object, default: null },
    locale: String,
});

const page = usePage();
const iconMap = { users: Users, clock: Clock, soup: Soup, wifi: Wifi };
const svcIconMap = { video: Video, heart: HandHeart, bed: Bed, bag: ShoppingBag };

// CMS-backed hero slides + services (editable via Admin → Settings).
const slides = computed(() => (page.props.heroSlides && page.props.heroSlides.length) ? page.props.heroSlides : [
    { img: '/storage/hero/temple-1.jpg', title: 'Talaja Temple Trust', sub: 'A sacred abode of devotion and service', tag: '|| Jay Mataji ||' },
    { img: '/storage/hero/temple-2.jpg', title: 'Connect With the Divine', sub: 'Live darshan, donations and blessings — anytime, anywhere', tag: '|| Om Namah Shivay ||' },
    { img: '/storage/hero/temple-3.jpg', title: 'A Legacy of Faith', sub: 'Serving devotees with devotion for generations', tag: '|| Har Har Mahadev ||' },
]);

const current = ref(0);
let interval;
const next = () => { current.value = (current.value + 1) % slides.value.length; };
const goTo = (i) => { current.value = i; clearInterval(interval); interval = setInterval(next, 5000); };
onMounted(() => { interval = setInterval(next, 5000); });
onUnmounted(() => clearInterval(interval));

const services = computed(() => (page.props.services || []).map((s) => ({
    icon: svcIconMap[s.icon] || Video,
    title: s.title,
    desc: s.desc,
    href: s.href,
    badge: s.badge,
    badgeColor: 'bg-red-500',
})));

// DB-backed stats (editable via Admin → Settings → home_stats), with fallback.
const stats = computed(() => (page.props.homeStats || []).map((s) => ({
    icon: iconMap[s.icon] || Users,
    value: s.value,
    label: s.label,
})));
</script>


<template>
    <AppLayout :locale="locale">
        <!-- Hero Slider -->
        <section class="relative h-[80vh] min-h-[500px] overflow-hidden">
            <transition-group tag="div" name="fade">
                <div v-for="(slide, i) in slides" v-show="current === i" :key="i" class="absolute inset-0">
                    <img :src="slide.img" :alt="slide.title" class="h-full w-full object-cover" />
                    <!-- Light overlay: stays bright and readable -->
                    <div class="absolute inset-0 bg-gradient-to-b from-maroon-900/30 via-maroon-900/20 to-maroon-900/50"></div>
                </div>
            </transition-group>

            <div class="relative z-10 flex h-full items-center justify-center px-4">
                <div class="max-w-3xl text-center text-white">
                    <transition name="fade" mode="out-in">
                        <div :key="current">
                            <p class="mb-3 font-serif text-xl tracking-wide text-saffron-300">{{ slides[current].tag }}</p>
                            <h1 class="mb-4 font-serif text-4xl font-bold drop-shadow-md md:text-6xl">{{ slides[current].title }}</h1>
                            <p class="mx-auto mb-8 max-w-xl text-lg text-white/90 md:text-xl">{{ slides[current].sub }}</p>
                        </div>
                    </transition>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="/donate" class="rounded-full bg-saffron-500 px-8 py-3.5 font-semibold text-white shadow-lg ring-1 ring-saffron-600 transition hover:bg-saffron-600 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-300 focus-visible:ring-offset-2">Donate Now</a>
                        <a href="/live-darshan" class="flex items-center gap-2 rounded-full border-2 border-white/70 bg-white/10 px-8 py-3.5 font-semibold text-white backdrop-blur transition hover:bg-white hover:text-maroon-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-pulse rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-red-400"></span>
                            </span>
                            Live Darshan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dots -->
            <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                <button v-for="(slide, i) in slides" :key="i" @click="goTo(i)" :aria-label="`Slide ${i+1}`" class="h-2.5 rounded-full transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-300 focus-visible:ring-offset-2" :class="current === i ? 'w-8 bg-saffron-500' : 'w-2.5 bg-white/50'"></button>
            </div>
        </section>

        <!-- Stats bar — white bg, saffron accents -->
        <section class="bg-white shadow-sm">
            <div class="mx-auto grid max-w-6xl grid-cols-2 border-b border-gray-100 py-8 md:grid-cols-4">
                <div v-for="s in stats" :key="s.label" class="flex items-center gap-4 border-b border-r border-gray-100 px-6 py-2 last:border-r-0 md:flex-col md:border-b-0 md:border-r md:py-0">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-saffron-50 text-saffron-600">
                        <component :is="s.icon" :size="22" :stroke-width="1.75" />
                    </div>
                    <div class="text-center">
                        <p class="font-serif text-2xl font-bold text-maroon-900 md:text-3xl">{{ s.value }}</p>
                        <p class="text-xs text-gray-500 md:text-sm">{{ s.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About teaser -->
        <section class="mx-auto max-w-7xl px-4 py-20">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Welcome to</p>
                    <h2 class="mb-5 font-serif text-3xl font-bold text-maroon-900 md:text-4xl">A Legacy of Devotion and Service</h2>
                    <div class="space-y-4 text-gray-600" v-html="temple?.translation?.about_trust || '<p>Nestled in the serene landscape of Talaja, our temple has been a beacon of faith for generations.</p>'"></div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/about-us" class="rounded-full bg-saffron-500 px-6 py-3 font-semibold text-white shadow-sm ring-1 ring-saffron-600 transition hover:bg-saffron-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500 focus-visible:ring-offset-2">Read More</a>
                        <a href="/trustees" class="rounded-full border border-gray-300 px-6 py-3 font-semibold text-gray-700 transition hover:border-saffron-400 hover:text-saffron-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500 focus-visible:ring-offset-2">Our Trustees</a>
                    </div>
                </div>
                <div class="relative">
                    <img src="/storage/about/temple.jpg" alt="Temple" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-lg" loading="lazy" />
                    <img src="/storage/about/lake.jpg" alt="Temple lake" class="absolute -bottom-6 -left-6 hidden h-44 w-44 rounded-2xl border-4 border-white object-cover shadow-xl md:block" loading="lazy" />
                    <div class="absolute -right-4 -top-4 rounded-2xl bg-white px-5 py-4 text-center shadow-lg ring-1 ring-gray-100">
                        <p class="font-serif text-2xl font-bold text-saffron-600">100+</p>
                        <p class="text-xs text-gray-500">Years of Devotion</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mb-12 text-center">
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Connect with the Divine</p>
                    <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Online Services and Offerings</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-gray-500">Stay connected with your faith, no matter where you are.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <a v-for="svc in services" :key="svc.title" :href="svc.href" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm transition hover:-translate-y-1 hover:border-saffron-200 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500">
                        <!-- Badge -->
                        <span v-if="svc.badge" class="absolute right-4 top-4 flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold text-white" :class="svc.badgeColor">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="absolute inline-flex h-full w-full animate-pulse rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                            </span>
                            {{ svc.badge }}
                        </span>
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-saffron-50 text-saffron-600 transition group-hover:-translate-y-1 group-hover:bg-saffron-100">
                            <component :is="svc.icon" :size="32" :stroke-width="1.75" />
                        </div>
                        <h3 class="mb-2 font-serif text-xl font-semibold text-maroon-900">{{ svc.title }}</h3>
                        <p class="text-sm text-gray-500">{{ svc.desc }}</p>
                        <span class="mt-4 inline-block text-sm font-medium text-saffron-500 transition group-hover:text-saffron-700">Learn more</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- News -->
        <section v-if="latestNews.length" class="mx-auto max-w-7xl px-4 py-20">
            <div class="mb-12 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Stay Updated</p>
                    <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">News and Updates</h2>
                </div>
                <a href="/news-and-updates" class="rounded-full border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-600 transition hover:border-saffron-400 hover:text-saffron-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500 focus-visible:ring-offset-2">View All</a>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <a v-for="n in latestNews" :key="n.id" :href="`/view-news/${n.slug}`" class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-saffron-200 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500">
                    <div class="aspect-video overflow-hidden bg-saffron-50">
                        <img v-if="n.image_path" :src="`/storage/${n.image_path}`" :alt="n.title" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-medium text-saffron-600">{{ n.published_at }}</p>
                        <h3 class="mt-1 font-serif text-lg font-semibold leading-snug text-maroon-900 transition group-hover:text-saffron-700">{{ n.title }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-gray-500" v-html="n.excerpt"></p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Trustees -->
        <section v-if="trustees.length" class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-4">
                <div class="mb-12 text-center">
                    <p class="mb-2 font-serif text-sm font-semibold uppercase tracking-widest text-saffron-600">Our Guiding Lights</p>
                    <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Board of Trustees</h2>
                </div>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="t in trustees" :key="t.name" class="text-center">
                        <div class="mx-auto mb-4 h-28 w-28 overflow-hidden rounded-full ring-4 ring-saffron-200">
                            <img v-if="t.photo_path" :src="`/storage/${t.photo_path}`" :alt="t.name" loading="lazy" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full w-full items-center justify-center bg-saffron-100 text-3xl font-bold text-saffron-600">{{ t.name?.charAt(0) }}</div>
                        </div>
                        <p class="font-serif text-lg font-semibold text-maroon-900">{{ t.name }}</p>
                        <p class="text-sm text-saffron-600">{{ t.designation }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-3xl px-4 text-center">
                <h2 class="font-serif text-3xl font-bold text-maroon-900 md:text-4xl">Begin Your Spiritual Journey</h2>
                <p class="mx-auto mt-3 max-w-xl text-gray-500">Join thousands of devotees. Donate, book your stay, or simply receive blessings.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="/donate" class="rounded-full bg-saffron-500 px-8 py-3.5 font-semibold text-white shadow-sm ring-1 ring-saffron-600 transition hover:bg-saffron-600 hover:shadow focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500 focus-visible:ring-offset-2">Donate Now</a>
                    <a href="/contact-us" class="rounded-full border border-gray-300 px-8 py-3.5 font-semibold text-gray-700 transition hover:border-saffron-400 hover:text-saffron-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-saffron-500 focus-visible:ring-offset-2">Contact Us</a>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.8s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>