<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import GuideTour from '@/Components/GuideTour.vue';
import {
    HandHeart, Phone, Mail, Menu, X, ChevronDown, User, LogOut,
    LayoutGrid, CalendarCheck, ShoppingBag, Wallet,
} from '@lucide/vue';

// Brand icons (YouTube/Instagram/Facebook) aren't in bundled lucide → inline SVG
const iconYoutube = 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z';
const iconInstagram = 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z';
const iconFacebook = 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z';

const props = defineProps({
    locale: { type: String, default: 'en' },
});

const nav = computed(() => usePage().props.nav || {});
const settings = computed(() => usePage().props.siteSettings || {});
const user = computed(() => usePage().props.auth?.user || null);
const isDevotee = computed(() => user.value?.type === 'devotee');
const currentUrl = computed(() => usePage().url || '/');
const year = new Date().getFullYear();

// --- menu definition (consolidated to avoid cramping) ---
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
    {
        label: 'Visit',
        children: [
            { label: nav.value.facilities || 'Facilities', href: '/community-welfare' },
            { label: 'Bookings', href: '/bookings' },
            { label: nav.value.shop || 'Shop', href: '/shop' },
        ],
    },
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

const isActive = (href) => {
    if (!href) return false;
    const url = currentUrl.value || '/';
    const path = String(url).split('?')[0];
    return path === href || (href !== '/' && path.startsWith(href));
};

const initials = (name) => {
    const n = typeof name === 'string' && name.trim() ? name : '?';
    return n.split(' ').map((w) => w.charAt(0) || '').join('').slice(0, 2).toUpperCase();
};

// --- open/close state ---
const mobileOpen = ref(false);
const openDropdown = ref(null);   // tracks which desktop dropdown label is open (click-based)
const openMobileSub = ref(null);  // tracks mobile accordion section
const userMenu = ref(false);

const toggleDropdown = (label) => { openDropdown.value = openDropdown.value === label ? null : label; };
const toggleMobileSub = (label) => { openMobileSub.value = openMobileSub.value === label ? null : label; };

const closeAll = () => { mobileOpen.value = false; openDropdown.value = null; openMobileSub.value = null; userMenu.value = false; };

// click outside to close desktop dropdowns + user menu
const onDocClick = (e) => {
    if (!e.target.closest('[data-nav-dropdown]') && !e.target.closest('[data-user-menu]')) {
        openDropdown.value = null;
        userMenu.value = false;
    }
};
const onEsc = (e) => { if (e.key === 'Escape') closeAll(); };
onMounted(() => { window.addEventListener('click', onDocClick); window.addEventListener('keydown', onEsc); });
onUnmounted(() => { window.removeEventListener('click', onDocClick); window.removeEventListener('keydown', onEsc); });

const logout = () => {
    const token = document.querySelector('meta[name=csrf-token]')?.content || '';
    const f = document.createElement('form');
    f.method = 'POST'; f.action = '/logout';
    f.innerHTML = `<input type="hidden" name="_token" value="${token}">`;
    document.body.appendChild(f); f.submit();
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-saffron-600 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

        <!-- Utility bar -->
        <div class="hidden bg-maroon-950 text-gray-300 lg:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-1.5 text-xs">
                <div class="flex items-center gap-5">
                    <a v-if="settings.phone" :href="`tel:${settings.phone}`" class="flex items-center gap-1.5 hover:text-saffron-300"><Phone :size="13" class="text-saffron-400" />{{ settings.phone }}</a>
                    <a v-if="settings.email" :href="`mailto:${settings.email}`" class="flex items-center gap-1.5 hover:text-saffron-300"><Mail :size="13" class="text-saffron-400" />{{ settings.email }}</a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <a href="#" aria-label="YouTube" class="text-gray-400 hover:text-saffron-300"><svg :width="15" :height="15" viewBox="0 0 24 24" fill="currentColor"><path :d="iconYoutube" /></svg></a>
                        <a href="#" aria-label="Instagram" class="text-gray-400 hover:text-saffron-300"><svg :width="15" :height="15" viewBox="0 0 24 24" fill="currentColor"><path :d="iconInstagram" /></svg></a>
                        <a href="#" aria-label="Facebook" class="text-gray-400 hover:text-saffron-300"><svg :width="15" :height="15" viewBox="0 0 24 24" fill="currentColor"><path :d="iconFacebook" /></svg></a>
                    </div>
                    <span class="text-gray-600">|</span>
                    <Link href="/change-language/en" class="font-medium hover:text-saffron-300" :class="{ 'text-saffron-300': locale === 'en' }">EN</Link>
                    <span class="text-gray-600">|</span>
                    <Link href="/change-language/gu" class="font-medium hover:text-saffron-300" :class="{ 'text-saffron-300': locale === 'gu' }">ગુ</Link>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 lg:px-6" aria-label="Main">
                <!-- Logo -->
                <Link href="/" class="flex shrink-0 items-center gap-2.5" @click="closeAll">
                    <img src="/storage/temple/logo.jpg" alt="Temple logo" class="h-10 w-10 rounded-full object-cover ring-2 ring-saffron-200 lg:h-11 lg:w-11" />
                    <div class="leading-tight">
                        <p class="font-serif text-sm font-bold text-maroon-900 lg:text-base">{{ settings.name || 'Talaja Temple Trust' }}</p>
                        <p class="hidden text-[10px] tracking-wider text-saffron-500 sm:block">|| Jay Mataji ||</p>
                    </div>
                </Link>

                <!-- Desktop menu -->
                <ul class="hidden items-center gap-0.5 lg:flex">
                    <template v-for="item in menu" :key="item.label">
                        <li class="relative" :data-nav-dropdown="item.children ? '1' : undefined">
                            <!-- With dropdown -->
                            <button v-if="item.children" @click.stop="toggleDropdown(item.label)" class="flex h-16 items-center gap-1 border-b-2 px-3 text-sm font-medium transition" :class="isActive(item.href) ? 'border-saffron-500 text-saffron-700' : 'border-transparent text-gray-700 hover:text-saffron-700'" :aria-expanded="openDropdown === item.label">
                                <span v-if="item.live" class="mr-1 h-1.5 w-1.5 animate-pulse rounded-full bg-red-500"></span>
                                {{ item.label }}
                                <ChevronDown :size="14" class="transition" :class="{ 'rotate-180': openDropdown === item.label }" />
                            </button>
                            <!-- Simple link -->
                            <Link v-else :href="item.href" class="flex h-16 items-center gap-1 border-b-2 px-3 text-sm font-medium transition" :class="isActive(item.href) ? 'border-saffron-500 text-saffron-700' : 'border-transparent text-gray-700 hover:text-saffron-700'">
                                <span v-if="item.live" class="mr-1 h-1.5 w-1.5 animate-pulse rounded-full bg-red-500"></span>
                                {{ item.label }}
                            </Link>

                            <!-- Dropdown panel (click-opened, no gap) -->
                            <transition enter-active-class="transition duration-150 ease-out" enter-from-class="-translate-y-1 opacity-0" enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="translate-y-0 opacity-100" leave-to-class="-translate-y-1 opacity-0">
                                <div v-if="item.children && openDropdown === item.label" class="absolute left-0 top-full w-56 border-t-2 border-saffron-500 bg-white py-2 shadow-xl ring-1 ring-gray-100">
                                    <Link v-for="child in item.children" :key="child.href" :href="child.href" class="block px-4 py-2.5 text-sm text-gray-700 transition hover:bg-saffron-50 hover:pl-5 hover:text-saffron-700" @click="openDropdown = null">{{ child.label }}</Link>
                                </div>
                            </transition>
                        </li>
                    </template>
                </ul>

                <!-- Right actions -->
                <div class="flex items-center gap-2">
                    <!-- Logged-in devotee: profile menu -->
                    <div v-if="isDevotee" data-user-menu="1" class="relative hidden lg:block">
                        <button @click.stop="userMenu = !userMenu" class="flex items-center gap-2 rounded-full border border-gray-200 py-1 pl-1 pr-2.5 transition hover:border-saffron-300 hover:bg-saffron-50" :aria-expanded="userMenu">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-saffron-500 to-saffron-700 text-[11px] font-bold text-white">{{ initials(user?.name) }}</span>
                            <ChevronDown :size="14" class="text-gray-400 transition" :class="{ 'rotate-180': userMenu }" />
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="-translate-y-1 opacity-0" enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="translate-y-0 opacity-100" leave-to-class="-translate-y-1 opacity-0">
                            <div v-if="userMenu" class="absolute right-0 top-full w-60 border-t-2 border-saffron-500 bg-white py-2 shadow-xl ring-1 ring-gray-100">
                                <div class="border-b border-gray-100 px-4 pb-2.5">
                                    <p class="truncate font-serif text-sm font-semibold text-maroon-900">{{ user?.name }}</p>
                                    <p class="truncate text-xs text-gray-400">{{ user?.email }}</p>
                                </div>
                                <Link href="/dashboard" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false"><LayoutGrid :size="16" /> My Account</Link>
                                <Link href="/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false"><User :size="16" /> Edit Profile</Link>
                                <Link href="/account/settings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false">⚙️ Account Settings</Link>
                                <div class="my-1 border-t border-gray-100"></div>
                                <Link href="/donate/my" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false"><Wallet :size="16" /> My Donations</Link>
                                <Link href="/bookings/my" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false"><CalendarCheck :size="16" /> My Bookings</Link>
                                <Link href="/shop/orders" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-saffron-50 hover:text-saffron-700" @click="userMenu=false"><ShoppingBag :size="16" /> My Orders</Link>
                                <div class="my-1 border-t border-gray-100"></div>
                                <button @click="logout" class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50"><LogOut :size="16" /> Logout</button>
                            </div>
                        </transition>
                    </div>
                    <!-- Logged out: login -->
                    <Link v-else href="/login" class="hidden items-center gap-1.5 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-saffron-300 hover:bg-saffron-50 hover:text-saffron-700 lg:inline-flex">
                        <User :size="16" /> Login
                    </Link>

                    <!-- Donate -->
                    <Link href="/donate" class="hidden items-center gap-1.5 rounded-full bg-saffron-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-saffron-600 hover:shadow sm:inline-flex">
                        <HandHeart :size="16" /> Donate
                    </Link>

                    <!-- Hamburger (mobile/tablet) -->
                    <button @click="mobileOpen = true" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-maroon-900 transition hover:bg-saffron-50 lg:hidden" aria-label="Open menu">
                        <Menu :size="24" />
                    </button>
                </div>
            </nav>
        </header>

        <!-- Mobile drawer overlay -->
        <transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="mobileOpen" class="fixed inset-0 z-50 bg-maroon-950/50 lg:hidden" @click="closeAll"></div>
        </transition>

        <!-- Mobile drawer -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="translate-x-full" enter-to-class="translate-x-0" leave-active-class="transition duration-200 ease-in" leave-from-class="translate-x-0" leave-to-class="translate-x-full">
            <aside v-if="mobileOpen" class="fixed right-0 top-0 z-50 flex h-full w-[85%] max-w-sm flex-col bg-white shadow-2xl lg:hidden">
                <!-- Drawer header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <img src="/storage/temple/logo.jpg" alt="Logo" class="h-9 w-9 rounded-full object-cover ring-1 ring-saffron-200" />
                        <p class="font-serif text-sm font-bold text-maroon-900">{{ settings.name }}</p>
                    </div>
                    <button @click="closeAll" class="rounded-lg p-2 text-gray-500 hover:bg-saffron-50" aria-label="Close menu"><X :size="22" /></button>
                </div>

                <!-- User chip -->
                <div v-if="isDevotee" class="flex items-center gap-3 border-b border-gray-100 bg-saffron-50 px-4 py-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-saffron-500 to-saffron-700 text-xs font-bold text-white">{{ initials(user?.name) }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-maroon-900">{{ user?.name }}</p>
                        <Link href="/profile" class="text-xs text-saffron-600 hover:underline" @click="closeAll">View profile</Link>
                    </div>
                </div>

                <!-- Drawer nav -->
                <nav class="flex-1 overflow-y-auto px-2 py-3" @click.stop>
                    <template v-for="item in menu" :key="item.label">
                        <div v-if="!item.children">
                            <Link :href="item.href" class="flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium transition" :class="isActive(item.href) ? 'bg-saffron-50 text-saffron-700' : 'text-gray-700 hover:bg-saffron-50'" @click="closeAll">
                                <span v-if="item.live" class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                                {{ item.label }}
                            </Link>
                        </div>
                        <div v-else>
                            <button @click="toggleMobileSub(item.label)" class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-sm font-medium text-gray-700 hover:bg-saffron-50">
                                <span>{{ item.label }}</span>
                                <ChevronDown :size="16" class="transition" :class="{ 'rotate-180': openMobileSub === item.label }" />
                            </button>
                            <transition enter-active-class="transition-all duration-200" enter-from-class="max-h-0 opacity-0" enter-to-class="max-h-60 opacity-100" leave-active-class="transition-all duration-150" leave-from-class="max-h-60 opacity-100" leave-to-class="max-h-0 opacity-0">
                                <div v-if="openMobileSub === item.label" class="overflow-hidden">
                                    <Link v-for="child in item.children" :key="child.href" :href="child.href" class="block rounded-lg py-2.5 pl-6 pr-3 text-sm text-gray-600 hover:bg-saffron-50 hover:text-saffron-700" @click="closeAll">{{ child.label }}</Link>
                                </div>
                            </transition>
                        </div>
                    </template>
                </nav>

                <!-- Drawer footer -->
                <div class="border-t border-gray-100 p-4">
                    <div class="grid grid-cols-2 gap-2">
                        <Link href="/donate" class="flex items-center justify-center gap-1.5 rounded-full bg-saffron-500 px-4 py-2.5 text-sm font-semibold text-white" @click="closeAll"><HandHeart :size="16" /> Donate</Link>
                        <Link v-if="isDevotee" href="/dashboard" class="flex items-center justify-center gap-1.5 rounded-full border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700" @click="closeAll"><LayoutGrid :size="16" /> Account</Link>
                        <Link v-else href="/login" class="flex items-center justify-center gap-1.5 rounded-full border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700" @click="closeAll"><User :size="16" /> Login</Link>
                    </div>
                    <div class="mt-3 flex items-center justify-center gap-4 text-xs">
                        <Link href="/change-language/en" :class="{ 'font-bold text-saffron-700': locale === 'en' }" @click="closeAll">English</Link>
                        <span class="text-gray-300">|</span>
                        <Link href="/change-language/gu" :class="{ 'font-bold text-saffron-700': locale === 'gu' }" @click="closeAll">ગુજરાતી</Link>
                    </div>
                    <button v-if="isDevotee" @click="logout" class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-full px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50"><LogOut :size="16" /> Logout</button>
                </div>
            </aside>
        </transition>

        <!-- Page content -->
        <main id="main-content" class="flex-1">
            <slot />
        </main>

        <!-- Guided tour (only for logged-in devotees who enabled Guide Mode) -->
        <GuideTour v-if="isDevotee && user?.guide_mode" />

        <!-- Footer -->
        <footer class="bg-maroon-950 text-gray-300">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-4 md:px-6">
                <div>
                    <div class="mb-4 flex items-center gap-3">
                        <img src="/storage/temple/logo.jpg" alt="Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-saffron-400/40" />
                        <p class="font-serif text-lg font-bold text-saffron-300">{{ settings.name }}</p>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400" v-html="settings.address"></p>
                    <div class="mt-4 flex gap-3">
                        <a href="#" aria-label="YouTube" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-saffron-600"><svg :width="16" :height="16" viewBox="0 0 24 24" fill="currentColor"><path :d="iconYoutube" /></svg></a>
                        <a href="#" aria-label="Instagram" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-saffron-600"><svg :width="16" :height="16" viewBox="0 0 24 24" fill="currentColor"><path :d="iconInstagram" /></svg></a>
                        <a href="#" aria-label="Facebook" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-saffron-600"><svg :width="16" :height="16" viewBox="0 0 24 24" fill="currentColor"><path :d="iconFacebook" /></svg></a>
                    </div>
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
                    <p class="flex items-center gap-2 text-sm text-gray-400"><Phone :size="14" class="text-saffron-400" />{{ settings.phone }}</p>
                    <p class="mt-1 flex items-center gap-2 text-sm text-gray-400"><Mail :size="14" class="text-saffron-400" />{{ settings.email }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-gray-500">
                {{ (nav.copyright || '© :year Talaja Temple Trust').replace(':year', year) }} · Designed with devotion.
            </div>
        </footer>
    </div>
</template>
