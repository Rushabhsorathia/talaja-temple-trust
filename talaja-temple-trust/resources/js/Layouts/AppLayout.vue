<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    locale: { type: String, default: 'en' },
});

const nav = computed(() => usePage().props.nav || {});
const settings = computed(() => usePage().props.siteSettings || {});
const year = new Date().getFullYear();
</script>

<template>
    <div class="flex min-h-screen flex-col bg-cream">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-saffron-600 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>
        <!-- Utility bar -->
        <div class="bg-maroon-900 text-cream">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 text-xs">
                <div class="flex items-center gap-4">
                    <a v-if="settings.phone" :href="`tel:${settings.phone}`" class="hover:text-saffron-300">{{ settings.phone }}</a>
                    <a v-if="settings.email" :href="`mailto:${settings.email}`" class="hover:text-saffron-300">{{ settings.email }}</a>
                </div>
                <div class="flex items-center gap-3">
                    <Link href="/change-language/en" class="hover:text-saffron-300" :class="{ 'font-bold text-saffron-300': locale === 'en' }">EN</Link>
                    <span>|</span>
                    <Link href="/change-language/gu" class="hover:text-saffron-300" :class="{ 'font-bold text-saffron-300': locale === 'gu' }">ગુ</Link>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="sticky top-0 z-40 border-b border-saffron-100 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
                <Link href="/" class="flex items-center gap-3">
                    <img v-if="settings.logo" :src="settings.logo" alt="Logo" class="h-12 w-12 rounded-full object-cover" />
                    <div class="h-12 w-12 rounded-full bg-temple-gradient" v-else></div>
                    <div>
                        <p class="font-serif text-lg font-bold leading-tight text-maroon-900">{{ settings.name || 'Talaja Temple Trust' }}</p>
                        <p class="text-xs text-saffron-600">{{ settings.tagline || '' }}</p>
                    </div>
                </Link>
                <nav class="hidden items-center gap-6 text-sm font-medium text-gray-700 lg:flex">
                    <Link href="/" class="hover:text-saffron-600">{{ nav.home || 'Home' }}</Link>
                    <Link href="/about-us" class="hover:text-saffron-600">{{ nav.about || 'About' }}</Link>
                    <Link href="/community-welfare" class="hover:text-saffron-600">{{ nav.facilities || 'Facilities' }}</Link>
                    <Link href="/gallery" class="hover:text-saffron-600">{{ nav.gallery || 'Gallery' }}</Link>
                    <Link href="/news-and-updates" class="hover:text-saffron-600">{{ nav.news || 'News' }}</Link>
                    <Link href="/live-darshan" class="flex items-center gap-1 hover:text-saffron-600">
                        <span class="inline-block h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                        {{ nav.live_darshan || 'Live Darshan' }}
                    </Link>
                </nav>
                <Link href="/donate" class="btn-temple !px-5 !py-2 text-sm">{{ nav.donate || 'Donate' }}</Link>
            </div>
        </header>

        <!-- Page content -->
        <main id="main-content" class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-maroon-950 text-cream/90">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-3">
                <div>
                    <h3 class="mb-3 font-serif text-xl text-saffron-300">{{ nav.reach_us || 'Reach Us' }}</h3>
                    <p class="text-sm leading-relaxed" v-html="settings.address || ''"></p>
                </div>
                <div>
                    <h3 class="mb-3 font-serif text-xl text-saffron-300">Information</h3>
                    <ul class="space-y-1.5 text-sm">
                        <li><Link href="/about-us" class="hover:text-saffron-300">{{ nav.the_trust }}</Link></li>
                        <li><Link href="/history" class="hover:text-saffron-300">{{ nav.history }}</Link></li>
                        <li><Link href="/trustees" class="hover:text-saffron-300">{{ nav.trustees }}</Link></li>
                        <li><Link href="/news-and-updates" class="hover:text-saffron-300">{{ nav.news }}</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-3 font-serif text-xl text-saffron-300">{{ nav.follow_us || 'Get Connected' }}</h3>
                    <p class="text-sm">{{ settings.phone }}</p>
                    <p class="text-sm">{{ settings.email }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-cream/60">
                {{ (nav.copyright || '© :year Talaja Temple Trust').replace(':year', year) }}
            </div>
        </footer>
    </div>
</template>
