<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({ donation: Object, orderId: String, amountPaisa: Number, razorpayKey: String, configured: Boolean, locale: String });
const message = ref('');

onMounted(() => {
    if (!props.configured) {
        // Dev fallback: auto-simulate successful payment.
        message.value = 'Simulating payment in dev mode...';
        setTimeout(() => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/donate/verify';
            const add = (n, v) => { const i = document.createElement('input'); i.name = n; i.value = v; form.appendChild(i); };
            add('donation_id', props.donation.id);
            add('razorpay_order_id', props.orderId);
            add('razorpay_payment_id', 'pay_mock_' + Date.now());
            add('razorpay_signature', 'mock_sig');
            document.body.appendChild(form);
            form.submit();
        }, 1200);
        return;
    }

    const rzp = new window.Razorpay({
        key: props.razorpayKey,
        amount: props.amountPaisa,
        currency: 'INR',
        order_id: props.orderId,
        name: 'Talaja Temple Trust',
        description: 'Donation',
        handler: function (response) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/donate/verify';
            const add = (n, v) => { const i = document.createElement('input'); i.name = n; i.value = v; form.appendChild(i); };
            add('donation_id', props.donation.id);
            add('razorpay_order_id', response.razorpay_order_id);
            add('razorpay_payment_id', response.razorpay_payment_id);
            add('razorpay_signature', response.razorpay_signature);
            document.body.appendChild(form);
            form.submit();
        },
        theme: { color: '#f06106' },
    });
    rzp.open();
});
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Processing Payment</title></Head>
        <section class="mx-auto max-w-md px-4 py-24 text-center">
            <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-saffron-200 border-t-saffron-600"></div>
            <p class="text-gray-600">{{ message || 'Opening secure payment...' }}</p>
        </section>
    </AppLayout>
</template>
