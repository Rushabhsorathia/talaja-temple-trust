<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { TriangleAlert } from '@lucide/vue';

defineProps({ categories: Array, slabs: Array, razorpayKey: String, configured: Boolean, locale: String, flash: Object });

const form = useForm({
    amount: '',
    donation_category_id: '',
    is_80g: false,
    donor_name: '',
    donor_email: '',
    donor_mobile: '',
    donor_pan: '',
    donor_address: '',
    is_anonymous: false,
    note: '',
});

const submit = () => form.post('/donate', { preserveScroll: true });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Donate</title></Head>
        <section class="bg-temple-gradient py-16 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Support the Temple</h1>
            <p class="mt-2 text-cream/90">Your contribution fuels devotion and service.</p>
        </section>

        <section class="mx-auto max-w-3xl px-4 py-12">
            <div v-if="flash?.error" class="mb-4 rounded-lg bg-red-50 p-3 text-red-700">{{ flash.error }}</div>
            <div v-if="!configured" class="mb-4 flex items-center gap-2 rounded-lg bg-amber-50 p-3 text-amber-700 text-sm"><TriangleAlert class="h-4 w-4 shrink-0" /> Payment gateway is in sandbox/off mode. Donations will be simulated.</div>

            <form @submit.prevent="submit" class="card-temple space-y-5">
                <div>
                    <label class="mb-2 block font-medium text-maroon-900">Choose an amount</label>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="s in slabs" :key="s" type="button" @click="form.amount = s" class="rounded-full border px-5 py-2 transition" :class="form.amount == s ? 'border-saffron-600 bg-saffron-600 text-white' : 'border-gray-200 text-gray-600'">₹{{ s }}</button>
                    </div>
                    <input v-model="form.amount" type="number" min="1" placeholder="Or enter custom amount" class="mt-3 w-full rounded-lg border-gray-300" required />
                </div>

                <div>
                    <label class="mb-2 block font-medium text-maroon-900">Category</label>
                    <select v-model="form.donation_category_id" class="w-full rounded-lg border-gray-300">
                        <option value="">General</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}{{ c.is_80g_eligible ? ' (80G)' : '' }}</option>
                    </select>
                </div>

                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.is_80g" class="rounded" />
                    <span>I require an 80G tax-exemption receipt (provide PAN below)</span>
                </label>

                <div v-if="form.is_80g" class="grid gap-3 sm:grid-cols-2">
                    <input v-model="form.donor_pan" placeholder="PAN Number" class="rounded-lg border-gray-300" />
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <input v-model="form.donor_name" placeholder="Name" class="rounded-lg border-gray-300" />
                    <input v-model="form.donor_mobile" placeholder="Mobile" class="rounded-lg border-gray-300" />
                    <input v-model="form.donor_email" type="email" placeholder="Email" class="rounded-lg border-gray-300" />
                    <input v-model="form.donor_address" placeholder="Address" class="rounded-lg border-gray-300" />
                </div>

                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.is_anonymous" class="rounded" />
                    <span>Keep my donation anonymous</span>
                </label>

                <button type="submit" class="btn-temple w-full" :disabled="form.processing">Proceed to Pay ₹{{ form.amount || 0 }}</button>
            </form>
        </section>
    </AppLayout>
</template>
