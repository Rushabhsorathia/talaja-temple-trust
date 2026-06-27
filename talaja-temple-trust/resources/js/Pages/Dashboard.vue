<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({ stats: Object, recentDonations: Array, locale: String });
const user = computed(() => usePage().props.auth?.user);

const statusColor = { success: 'bg-green-100 text-green-700', pending: 'bg-amber-100 text-amber-700', failed: 'bg-red-100 text-red-700' };

const cards = [
    { icon: '🤲', label: 'Total Donated', value: props.stats.totalDonated ? '₹' + Number(props.stats.totalDonated).toLocaleString('en-IN') : '₹0', sub: `${props.stats.donationCount} donation(s)`, href: '/donate/my', cta: 'My Donations' },
    { icon: '🛏️', label: 'Bookings', value: props.stats.bookings, sub: `${props.stats.activeBookings} active`, href: '/bookings/my', cta: 'My Bookings' },
    { icon: '🛍️', label: 'Orders', value: props.stats.orders, sub: 'shop orders', href: '/shop/orders', cta: 'My Orders' },
];

const actions = [
    { icon: '🤲', title: 'Donate', desc: 'Support the temple', href: '/donate' },
    { icon: '🛏️', title: 'Book a Room', desc: 'Plan your stay', href: '/bookings' },
    { icon: '🛍️', title: 'Shop', desc: 'Prasad & souvenirs', href: '/shop' },
    { icon: '👁️', title: 'Live Darshan', desc: 'Watch now', href: '/live-darshan' },
];
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>My Account</title></Head>

        <!-- Welcome -->
        <section class="bg-gradient-to-br from-saffron-500 to-saffron-700 py-12 text-white">
            <div class="mx-auto max-w-6xl px-4">
                <p class="font-serif text-lg text-cream/90">|| Jay Mataji ||</p>
                <h1 class="font-serif text-3xl font-bold md:text-4xl">Welcome, {{ user?.name || 'Devotee' }} 🙏</h1>
                <p class="mt-2 text-cream/80">May the divine bless you and your family.</p>
            </div>
        </section>

        <!-- Stats -->
        <section class="mx-auto max-w-6xl px-4 py-10">
            <div class="grid gap-5 sm:grid-cols-3">
                <Link v-for="c in cards" :key="c.label" :href="c.href" class="group rounded-2xl border border-saffron-100 bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl">{{ c.icon }}</span>
                        <span class="text-sm font-medium text-saffron-600 opacity-0 transition group-hover:opacity-100">{{ c.cta }} →</span>
                    </div>
                    <p class="mt-3 font-serif text-3xl font-bold text-maroon-900">{{ c.value }}</p>
                    <p class="text-sm text-gray-500">{{ c.label }} · {{ c.sub }}</p>
                </Link>
            </div>
        </section>

        <!-- Quick actions -->
        <section class="mx-auto max-w-6xl px-4 pb-10">
            <h2 class="mb-5 font-serif text-2xl font-bold text-maroon-900">Quick Actions</h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a v-for="a in actions" :key="a.title" :href="a.href" class="flex items-center gap-3 rounded-2xl border border-saffron-100 bg-white p-5 shadow-sm transition hover:border-saffron-300 hover:shadow-md">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-saffron-50 text-2xl">{{ a.icon }}</span>
                    <div>
                        <p class="font-serif font-semibold text-maroon-900">{{ a.title }}</p>
                        <p class="text-xs text-gray-500">{{ a.desc }}</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Recent donations -->
        <section class="mx-auto max-w-6xl px-4 pb-16">
            <div class="flex items-center justify-between">
                <h2 class="mb-5 font-serif text-2xl font-bold text-maroon-900">Recent Donations</h2>
                <Link href="/donate/my" class="text-sm font-medium text-saffron-600 hover:underline">View all</Link>
            </div>
            <div class="overflow-hidden rounded-2xl border border-saffron-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-saffron-50 text-left text-gray-600">
                        <tr>
                            <th class="px-5 py-3 font-medium">Receipt</th>
                            <th class="px-5 py-3 font-medium">Category</th>
                            <th class="px-5 py-3 font-medium">Date</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 text-right font-medium">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="d in recentDonations" :key="d.receipt_no" class="border-t border-saffron-50">
                            <td class="px-5 py-3 font-medium text-gray-700">{{ d.receipt_no }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ d.category || 'General' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ d.paid_at || '-' }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2 py-0.5 text-xs capitalize" :class="statusColor[d.status]">{{ d.status }}</span></td>
                            <td class="px-5 py-3 text-right font-semibold text-maroon-900">₹{{ Number(d.amount).toLocaleString('en-IN') }}</td>
                        </tr>
                        <tr v-if="!recentDonations.length">
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400">No donations yet. <Link href="/donate" class="text-saffron-600 hover:underline">Make your first donation →</Link></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </AppLayout>
</template>
