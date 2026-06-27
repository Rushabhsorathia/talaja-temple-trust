<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ donations: Array, total: Number, locale: String });
const statusColor = { success: 'bg-green-100 text-green-700', pending: 'bg-amber-100 text-amber-700', failed: 'bg-red-100 text-red-700' };
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>My Donations</title></Head>
        <section class="mx-auto max-w-4xl px-4 py-12">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="font-serif text-3xl text-maroon-900">My Donations</h1>
                <div class="card-temple !py-3 text-right">
                    <p class="text-xs text-gray-500">Total Contributed</p>
                    <p class="font-serif text-2xl text-saffron-600">₹{{ total }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div v-for="d in donations" :key="d.id" class="card-temple flex items-center justify-between">
                    <div>
                        <p class="font-serif text-maroon-900">{{ d.category || 'General' }} — ₹{{ d.amount }}</p>
                        <p class="text-xs text-gray-500">{{ d.receipt_no }} · {{ d.paid_at || '-' }}<span v-if="d.is_80g"> · 80G</span></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full px-2 py-0.5 text-xs" :class="statusColor[d.status]">{{ d.status }}</span>
                        <a v-for="r in d.receipts" :key="r.type" :href="r.url" target="_blank" class="text-xs text-saffron-600 hover:underline">{{ r.type==='80g'?'80G':'Receipt' }} PDF</a>
                    </div>
                </div>
                <p v-if="!donations.length" class="text-center text-gray-500">No donations yet. <a href="/donate" class="text-saffron-600 hover:underline">Donate now</a>.</p>
            </div>
        </section>
    </AppLayout>
</template>
