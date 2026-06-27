<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ bookings: Array, locale: String, flash: Object });
const statusColor = { confirmed: 'bg-green-100 text-green-700', pending: 'bg-amber-100 text-amber-700', checked_in: 'bg-blue-100 text-blue-700', cancelled: 'bg-red-100 text-red-700' };
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>My Bookings</title></Head>
        <section class="mx-auto max-w-4xl px-4 py-12">
            <h1 class="mb-6 font-serif text-3xl text-maroon-900">My Bookings</h1>
            <div v-if="flash?.success" class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ flash.success }}</div>
            <div class="space-y-3">
                <div v-for="b in bookings" :key="b.booking_no" class="card-temple flex items-center justify-between">
                    <div>
                        <p class="font-serif text-maroon-900">{{ b.title }}</p>
                        <p class="text-xs text-gray-500">{{ b.booking_no }} · {{ b.check_in }}{{ b.check_out ? ' → '+b.check_out : '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">₹{{ b.amount }}</p>
                        <span class="rounded-full px-2 py-0.5 text-xs" :class="statusColor[b.status]">{{ b.status }}</span>
                    </div>
                </div>
                <p v-if="!bookings.length" class="text-center text-gray-500">No bookings yet.</p>
            </div>
        </section>
    </AppLayout>
</template>
