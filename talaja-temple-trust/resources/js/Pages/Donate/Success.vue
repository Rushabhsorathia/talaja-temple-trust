<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
defineProps({ donation: Object, receiptUrl: String, locale: String });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Donation Successful</title></Head>
        <section class="mx-auto max-w-md px-4 py-20 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600"><Check class="h-8 w-8" :stroke-width="3" /></div>
            <h1 class="font-serif text-2xl text-maroon-900">Thank You!</h1>
            <p class="mt-2 text-gray-600">Your donation of <strong>₹{{ donation.amount }}</strong> has been received.</p>
            <p class="text-sm text-gray-500">Receipt No: {{ donation.receipt_no }}</p>
            <a :href="receiptUrl" target="_blank" class="btn-temple mt-6">Download Receipt</a>
            <Link href="/" class="mt-3 block text-saffron-600 hover:underline">Back to Home</Link>
        </section>
    </AppLayout>
</template>
