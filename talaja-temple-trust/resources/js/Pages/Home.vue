<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    banners: { type: Array, default: () => [] },
    latestNews: { type: Array, default: () => [] },
    galleryPreview: { type: Array, default: () => [] },
    trustees: { type: Array, default: () => [] },
    temple: { type: Object, default: null },
    locale: String,
});
</script>

<template>
    <AppLayout :locale="locale">
        <!-- Hero -->
        <section class="relative flex h-[70vh] min-h-[480px] items-center justify-center overflow-hidden bg-temple-gradient">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative z-10 px-4 text-center text-white">
                <p class="mb-3 font-serif text-2xl text-saffron-200">|| Jay Mataji ||</p>
                <h1 class="mb-4 font-serif text-4xl font-bold drop-shadow md:text-6xl">{{ temple?.name || 'Talaja Temple Trust' }}</h1>
                <p class="mx-auto mb-8 max-w-2xl text-lg text-cream/90">A sacred abode of devotion and service. Connect with the divine through darshan, seva, and community.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link href="/contact-us" class="btn-temple">Donate Now</Link>
                    <a href="#" class="rounded-full border border-white px-6 py-3 font-semibold text-white transition hover:bg-white hover:text-maroon-900">Live Darshan</a>
                </div>
            </div>
        </section>

        <!-- About teaser -->
        <section class="mx-auto max-w-7xl px-4 py-16">
            <div class="grid items-center gap-10 md:grid-cols-2">
                <div>
                    <h2 class="section-title !text-left">A Legacy of Devotion and Service</h2>
                    <p class="mt-4 text-gray-600" v-html="temple?.translation?.about_trust || 'Welcome to the sacred abode where the divine presence continues to inspire countless hearts. The trust offers a range of services, facilities, and online offerings.'"></p>
                    <Link href="/about-us" class="btn-temple mt-6">Read More</Link>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="aspect-square rounded-2xl bg-saffron-100"></div>
                    <div class="mt-8 aspect-square rounded-2xl bg-maroon-100"></div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="bg-saffron-50 py-16">
            <div class="mx-auto max-w-7xl px-4">
                <h2 class="section-title">Online Services & Offerings</h2>
                <p class="section-subtitle">Stay connected with your faith, wherever you are.</p>
                <div class="grid gap-6 md:grid-cols-4">
                    <div class="card-temple text-center">
                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-saffron-100 text-2xl">🛕</div>
                        <h3 class="font-serif text-lg">Live Darshan</h3>
                    </div>
                    <div class="card-temple text-center">
                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-saffron-100 text-2xl">🤲</div>
                        <h3 class="font-serif text-lg">Donate</h3>
                    </div>
                    <div class="card-temple text-center">
                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-saffron-100 text-2xl">🛏️</div>
                        <h3 class="font-serif text-lg">Bookings</h3>
                    </div>
                    <div class="card-temple text-center">
                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-saffron-100 text-2xl">🛍️</div>
                        <h3 class="font-serif text-lg">Shop</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- News -->
        <section v-if="latestNews.length" class="mx-auto max-w-7xl px-4 py-16">
            <div class="mb-8 flex items-end justify-between">
                <h2 class="section-title !mb-0 !text-left">News & Updates</h2>
                <Link href="/news-and-updates" class="text-saffron-600 hover:underline">View All</Link>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <Link v-for="n in latestNews" :key="n.id" :href="`/view-news/${n.slug}`" class="card-temple overflow-hidden !p-0 transition hover:shadow-lg">
                    <div class="aspect-video bg-saffron-100"></div>
                    <div class="p-4">
                        <p class="text-xs text-saffron-600">{{ n.published_at }}</p>
                        <h3 class="mt-1 font-serif text-lg text-maroon-900">{{ n.title }}</h3>
                    </div>
                </Link>
            </div>
        </section>

        <!-- Trustees -->
        <section v-if="trustees.length" class="bg-maroon-950 py-16">
            <div class="mx-auto max-w-7xl px-4">
                <h2 class="mb-8 text-center font-serif text-3xl text-saffron-300">Our Trustees</h2>
                <div class="grid gap-6 md:grid-cols-4">
                    <div v-for="t in trustees" :key="t.name" class="text-center text-cream">
                        <div class="mx-auto mb-3 h-24 w-24 rounded-full bg-saffron-500"></div>
                        <p class="font-serif text-lg">{{ t.name }}</p>
                        <p class="text-sm text-saffron-300">{{ t.designation }}</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
