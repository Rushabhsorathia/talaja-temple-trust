<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ orders: Array, locale: String });
const payColor = { paid: 'bg-green-100 text-green-700', pending: 'bg-amber-100 text-amber-700', failed: 'bg-red-100 text-red-700' };
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>My Orders</title></Head>
        <section class="mx-auto max-w-4xl px-4 py-12">
            <h1 class="mb-6 font-serif text-3xl text-maroon-900">My Orders</h1>
            <div class="space-y-3">
                <div v-for="o in orders" :key="o.order_no" class="card-temple">
                    <div class="flex items-center justify-between">
                        <p class="font-serif text-maroon-900">{{ o.order_no }}</p>
                        <span class="rounded-full px-2 py-0.5 text-xs" :class="payColor[o.payment_status]">{{ o.payment_status }}</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ o.created_at }} · {{ o.fulfilment_status }}</p>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li v-for="(i, idx) in o.items" :key="idx">{{ i.qty }} × {{ i.name }} (₹{{ i.price }})</li>
                    </ul>
                    <p class="mt-2 text-right font-semibold text-saffron-600">₹{{ o.total }}</p>
                </div>
                <p v-if="!orders.length" class="text-center text-gray-500">No orders yet.</p>
            </div>
        </section>
    </AppLayout>
</template>
