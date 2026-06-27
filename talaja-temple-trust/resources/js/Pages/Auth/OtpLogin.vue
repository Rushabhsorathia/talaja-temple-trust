<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ status: String, errors: Object, mobile: String });

const sendForm = useForm({ mobile: '' });
const verifyForm = useForm({ mobile: '', otp: '', name: '' });

const sendOtp = () => sendForm.post('/otp/send', {
    preserveScroll: true,
    onSuccess: () => { verifyForm.mobile = sendForm.mobile; },
});
const verifyOtp = () => verifyForm.post('/otp/verify');
</script>

<template>
    <GuestLayout>
        <Head><title>Devotee Login (OTP)</title></Head>
        <div class="mx-auto max-w-md">
            <h1 class="mb-6 text-center font-serif text-2xl text-maroon-900">Devotee Login</h1>

            <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ status }}</div>

            <form @submit.prevent="sendOtp" class="mb-6">
                <label class="mb-1 block text-sm font-medium">Mobile Number</label>
                <input v-model="sendForm.mobile" type="tel" pattern="[6-9][0-9]{9}" placeholder="10-digit mobile" class="w-full rounded-lg border-gray-300" required />
                <p v-if="sendForm.errors.mobile" class="mt-1 text-sm text-red-600">{{ sendForm.errors.mobile }}</p>
                <button type="submit" class="btn-temple mt-3 w-full" :disabled="sendForm.processing">Send OTP</button>
            </form>

            <form @submit.prevent="verifyOtp">
                <input v-model="verifyForm.mobile" type="hidden" />
                <input v-model="verifyForm.name" placeholder="Name (for new devotees)" class="mb-3 w-full rounded-lg border-gray-300" />
                <label class="mb-1 block text-sm font-medium">Enter OTP</label>
                <input v-model="verifyForm.otp" inputmode="numeric" maxlength="6" placeholder="6-digit OTP" class="w-full rounded-lg border-gray-300" required />
                <p v-if="errors?.otp" class="mt-1 text-sm text-red-600">{{ errors.otp }}</p>
                <button type="submit" class="btn-temple mt-3 w-full" :disabled="verifyForm.processing">Verify & Login</button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-500">
                <a href="/login" class="text-saffron-600 hover:underline">Login with email/password</a>
            </p>
        </div>
    </GuestLayout>
</template>
