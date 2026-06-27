<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    locale: { type: String, default: 'en' },
});

const nav = computed(() => usePage().props.nav || {});
const settings = computed(() => usePage().props.siteSettings || {});
const year = new Date().getFullYear();

const mobileOpen = ref(false);
const aboutOpen = ref(false);

const menu = computed(() => [
    { label: nav.value.home || 'Home', href: '/' },
    {
        label: nav.value.about || 'About',
        children: [
            { label: nav.value.the_trust || 'The Trust', href: '/about-us' },
            { label: nav.value.history || 'History', href: '/history' },
            { label: nav.value.trustees || 'Trustees', href: '/trustees' },
        ],
    },
    { label: nav.value.temple_info || 'Temple Info', href: '/temple-info' },
    { label: nav.value.facilities || 'Facilities', href: '/community-welfare' },
    {
        label: nav.value.gallery || 'Gallery',
        children: [
            { label: nav.value.photo_gallery || 'Photo Gallery', href: '/photo-gallery' },
            { label: nav.value.video_gallery || 'Video Gallery', href: '/video-gallery' },
        ],
    },
    { label: nav.value.news || 'News', href: '/news-and-updates' },
    { label: nav.value.live_darshan || 'Live Darshan', href: '/live-darshan', live: true },
]);

const closeAll = () => { mobileOpen.value = false; aboutOpen.value = false; };
const onEsc = (e) => { if (e.key === 'Escape') closeAll(); };
onMounted(() => window.addEventListener('keydown', onEsc));
onUnmounted(() => window.removeEventListener('keydown', onEsc));
</script>

<template>
    <div class="flex min-h-screen flex-col bg-cream">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-saffron-600 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

        <!-- Utility bar -->
        <div class="hidden bg-maroon-950 text-cream/80 md:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 text-xs">
                <div class="flex items-center gap-4">
                    <a v-if="settings.phone" :href="`tel:${settings.phone}`" class="flex items-center gap-1 hover:text-saffron-300">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        {{ settings.phone }}
                    </a>
                    <a v-if="settings.email" :href="`mailto:${settings.email}`" class="hover:text-saffron-300">{{ settings.email }}</a>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-saffron-300">Follow us:</span>
                    <a href="#" aria-label="YouTube" class="hover:text-saffron-300">▶</a>
                    <a href="#" aria-label="Instagram" class="hover:text-saffron-300">◉</a>
                    <span class="mx-1 opacity-30">|</span>
                    <Link href="/change-language/en" class="hover:text-saffron-300" :class="{ 'font-bold text-saffron-300': locale === 'en' }">EN</Link>
                    <span class="opacity-30">|</span>
                    <Link href="/change-language/gu" class="hover:text-saffron-300" :class="{ 'font-bold text-saffron-300': locale === 'gu' }">ગુ</Link>
                </div>
            </div>
        </div>

        <!-- Header / Navbar -->
        <header class="sticky top-0 z-40 border-b border-saffron-100 bg-white/95 shadow-sm backdrop-blur">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3" aria-label="Main">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-3" @click="closeAll">
                    <img src="/storage/temple/logo.jpg" alt="Temple logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-saffron-200" />
                    <div class="leading-tight">
                        <p class="font-serif text-base font-bold text-maroon-900 md:text-lg">{{ settings.name || 'Talaja Temple Trust' }}</p>
                        <p class="text-[10px] tracking-wide text-saffron-600">|| Jay Mataji ||</p>
                    </div>
                </Link>

                <!-- Desktop menu -->
                <ul class="hidden items-center gap-1 lg:flex">
                    <template v-for="item in menu" :key="item.label">
                        <li class="group relative">
                            <component
                                :is="item.href ? Link : 'span'"
                                :href="item.href"
                                class="flex cursor-pointer items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-saffron-50 hover:text-saffron-700"
                            >
                                <span v-if="item.live" class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                {{ item.label }}
                                <svg v-if="item.children" class="h-3 w-3 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </component>
                            <!-- Dropdown -->
                            <div v-if="item.children" class="invisible absolute left-0 top-full z-50 w-52 translate-y-1 rounded-xl border border-saffron-100 bg-white py-2 opacity-0 shadow-xl transition-all group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                                <Link v-for="child in item.children" :key="child.href" :href="child.href" class="block px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700">{{ child.label }}</Link>
                            </div>
                        </li>
                    </template>
                </ul>

                <!-- Right actions -->
                <div class="flex items-center gap-2">
                    <Link href="/donate" class="hidden rounded-full bg-gradient-to-r from-saffron-500 to-saffron-700 px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:shadow-lg hover:brightness-110 sm:inline-flex">
                        🤲 Donate
                    </Link>
                    <!-- Hamburger -->
                    <button @click="mobileOpen = !mobileOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-maroon-900 hover:bg-saffron-50 lg:hidden" aria-label="Toggle menu" :aria-expanded="mobileOpen">
                        <svg v-if="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </nav>

            <!-- Mobile drawer -->
            <transition
                enter-active-class="transition duration-200 ease-out" enter-from-class="-translate-y-2 opacity-0" enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in" leave-from-class="translate-y-0 opacity-100" leave-to-class="-translate-y-2 opacity-0"
            >
                <div v-if="mobileOpen" class="border-t border-saffron-100 bg-white lg:hidden">
                    <ul class="space-y-1 px-4 py-3">
                        <template v-for="item in menu" :key="item.label">
                            <li v-if="!item.children">
                                <Link :href="item.href" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-saffron-50" @click="closeAll">
                                    <span v-if="item.live" class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                    {{ item.label }}
                                </Link>
                            </li>
                            <li v-else>
                                <button @click="aboutOpen = !aboutOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-saffron-50">
                                    {{ item.label }}
                                    <svg class="h-4 w-4 transition" :class="{ 'rotate-180': aboutOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <transition enter-active-class="transition-all duration-200" enter-from-class="max-h-0 opacity-0" enter-to-class="max-h-60 opacity-100" leave-active-class="transition-all duration-150" leave-from-class="max-h-60 opacity-100" leave-to-class="max-h-0 opacity-0">
                                    <ul v-if="aboutOpen" class="ml-3 overflow-hidden border-l-2 border-saffron-100 pl-3">
                                        <li v-for="child in item.children" :key="child.href">
                                            <Link :href="child.href" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-saffron-50" @click="closeAll">{{ child.label }}</Link>
                                        </li>
                                    </ul>
                                </transition>
                            </li>
                        </template>
                        <li class="pt-2">
                            <Link href="/donate" class="flex justify-center rounded-full bg-gradient-to-r from-saffron-500 to-saffron-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md" @click="closeAll">🤲 Donate</Link>
                        </li>
                        <li class="flex items-center justify-center gap-4 pt-2 text-xs">
                            <Link href="/change-language/en" :class="{ 'font-bold text-saffron-700': locale === 'en' }">English</Link>
                            <span class="opacity-30">|</span>
                            <Link href="/change-language/gu" :class="{ 'font-bold text-saffron-700': locale === 'gu' }">ગુજરાતી</Link>
                        </li>
                    </ul>
                </div>
            </transition>
        </header>

        <!-- Page content -->
        <main id="main-content" class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-maroon-950 text-cream/80">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-4">
                <div class="md:col-span-1">
                    <div class="mb-4 flex items-center gap-3">
                        <img src="/storage/temple/logo.jpg" alt="Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-saffron-400/40" />
                        <p class="font-serif text-lg font-bold text-saffron-300">{{ settings.name }}</p>
                    </div>
                    <p class="text-sm leading-relaxed text-cream/60" v-html="settings.address"></p>
                </div>
                <div>
                    <h3 class="mb-4 font-serif text-base font-semibold text-saffron-300">Information</h3>
                    <ul class="space-y-2 text-sm">
                        <li><Link href="/about-us" class="hover:text-saffron-300">About Trust</Link></li>
                        <li><Link href="/history" class="hover:text-saffron-300">History</Link></li>
                        <li><Link href="/trustees" class="hover:text-saffron-300">Trustees</Link></li>
                        <li><Link href="/news-and-updates" class="hover:text-saffron-300">News & Updates</Link></li>
                        <li><Link href="/faqs" class="hover:text-saffron-300">FAQs</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 font-serif text-base font-semibold text-saffron-300">Services</h3>
                    <ul class="space-y-2 text-sm">
                        <li><Link href="/donate" class="hover:text-saffron-300">Donate</Link></li>
                        <li><Link href="/bookings" class="hover:text-saffron-300">Bookings</Link></li>
                        <li><Link href="/shop" class="hover:text-saffron-300">Shop</Link></li>
                        <li><Link href="/live-darshan" class="hover:text-saffron-300">Live Darshan</Link></li>
                        <li><Link href="/contact-us" class="hover:text-saffron-300">Contact Us</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 font-serif text-base font-semibold text-saffron-300">Reach Us</h3>
                    <p class="text-sm text-cream/60">{{ settings.phone }}</p>
                    <p class="text-sm text-cream/60">{{ settings.email }}</p>
                    <div class="mt-4 flex gap-3">
                        <a href="#" aria-label="YouTube" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-saffron-600">▶</a>
                        <a href="#" aria-label="Instagram" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-saffron-600">◉</a>
                        <a href="#" aria-label="Facebook" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-saffron-600">f</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-cream/50">
                {{ (nav.copyright || '© :year Talaja Temple Trust').replace(':year', year) }} · Designed with devotion.
            </div>
        </footer>
    </div>
</template>
