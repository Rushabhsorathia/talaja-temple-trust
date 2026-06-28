<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { X, ChevronRight, ChevronLeft, Check, Compass } from '@lucide/vue';

const DISMISS_KEY = 'guide_tour_dismissed';

// Tour steps — text only (no element targeting needed), always reliable.
const steps = [
    { icon: '🛕', title: 'Welcome to Talaja Temple Trust', body: 'A quick tour of the key features. You can re-enable this anytime from Account Settings → Guide Mode.' },
    { icon: '🤲', title: 'Donate', body: 'Click the orange “Donate” button to make a secure online donation. Tax-exempt 80G receipts are generated automatically.' },
    { icon: '🛏️', title: 'Book a Room or Hall', body: 'Under “Visit” in the menu you can check room availability and book accommodation or a meeting hall for your event.' },
    { icon: '🛍️', title: 'Shop', body: 'Browse the Shop for prasadam, books and souvenirs. Add to cart and check out in a few clicks.' },
    { icon: '👁️', title: 'Live Darshan', body: 'Watch live aarti and darshan from anywhere in the world via the “Live Darshan” menu item.' },
    { icon: '👤', title: 'Your Account', body: 'Use the profile menu (top-right) to view your donations, bookings, orders and edit your profile.' },
    { icon: '🌐', title: 'Language', body: 'Switch between English and Gujarati (ગુ) from the top bar at any time.' },
    { icon: '✅', title: "You're all set!", body: 'That’s the tour. You can turn Guide Mode off from Account Settings.' },
];

const current = ref(0);
const show = ref(false);

const step = computed(() => steps[current.value]);
const isLast = computed(() => current.value === steps.length - 1);

const next = () => { if (!isLast.value) current.value++; else finish(); };
const prev = () => { if (current.value > 0) current.value--; };
const finish = () => { show.value = false; localStorage.setItem(DISMISS_KEY, '1'); };

onMounted(() => {
    const on = usePage().props.auth?.user?.guide_mode;
    const dismissed = localStorage.getItem(DISMISS_KEY) === '1';
    // show when guide_mode is on and not dismissed this session
    if (on && !dismissed) {
        show.value = true;
    }
});
</script>

<template>
    <teleport to="body">
        <!-- Floating re-open button (when dismissed but guide still on) -->
        <button
            v-if="!show"
            class="fixed bottom-6 right-6 z-40 flex items-center gap-2 rounded-full bg-saffron-500 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-saffron-600"
            @click="current = 0; show = true"
            title="Start guided tour"
        >
            <Compass :size="18" /> Tour
        </button>

        <div v-if="show" class="fixed inset-0 z-[80] flex items-center justify-center bg-maroon-950/60 p-4 backdrop-blur-sm" @click.self="finish">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                <!-- header -->
                <div class="relative bg-gradient-to-br from-saffron-500 to-saffron-700 p-6 text-white">
                    <button class="absolute right-4 top-4 text-white/80 hover:text-white" @click="finish" aria-label="Close"><X :size="20" /></button>
                    <div class="mb-2 text-4xl">{{ step.icon }}</div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-cream/80">Step {{ current + 1 }} of {{ steps.length }}</p>
                    <h2 class="mt-1 font-serif text-xl font-bold">{{ step.title }}</h2>
                </div>

                <!-- body -->
                <div class="p-6">
                    <p class="text-sm leading-relaxed text-gray-600">{{ step.body }}</p>

                    <!-- progress dots -->
                    <div class="mt-5 flex gap-1.5">
                        <span v-for="(s, i) in steps" :key="i" class="h-1.5 rounded-full transition-all" :class="i === current ? 'w-6 bg-saffron-500' : 'w-1.5 bg-gray-200'"></span>
                    </div>

                    <!-- actions -->
                    <div class="mt-6 flex items-center justify-between">
                        <button v-if="current > 0" class="flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-maroon-900" @click="prev"><ChevronLeft :size="16" /> Back</button>
                        <span v-else></span>
                        <div class="flex gap-2">
                            <button class="text-sm font-medium text-gray-400 hover:text-gray-600" @click="finish">Skip</button>
                            <button class="flex items-center gap-1 rounded-full bg-saffron-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-saffron-600" @click="next">
                                <template v-if="isLast"><Check :size="16" /> Done</template>
                                <template v-else>Next <ChevronRight :size="16" /></template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>
