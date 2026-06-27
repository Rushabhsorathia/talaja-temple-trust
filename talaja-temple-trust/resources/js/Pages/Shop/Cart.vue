<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
defineProps({ items: Array, total: Number, locale: String });

const checkout = useForm({ customer_name: '', customer_email: '', customer_mobile: '', shipping_address: '' });
const removeForm = useForm({});
const remove = (id) => removeForm.post(`/shop/remove`, { data: { product_id: id }, preserveScroll: true });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Cart</title></Head>
        <section class="mx-auto max-w-4xl px-4 py-12">
            <h1 class="mb-6 font-serif text-3xl text-maroon-900">Your Cart</h1>
            <div v-if="items.length" class="grid gap-8 md:grid-cols-2">
                <div class="space-y-3">
                    <div v-for="i in items" :key="i.id" class="card-temple flex items-center justify-between">
                        <div><p class="font-medium text-maroon-900">{{ i.name }}</p><p class="text-sm text-gray-500">₹{{ i.price }} × {{ i.qty }}</p></div>
                        <div class="flex items-center gap-3">
                            <span class="font-semibold">₹{{ i.subtotal }}</span>
                            <button @click="remove(i.id)" class="text-red-500 text-sm">Remove</button>
                        </div>
                    </div>
                </div>
                <div class="card-temple">
                    <h3 class="mb-3 font-serif text-lg text-maroon-900">Checkout</h3>
                    <form @submit.prevent="checkout.post('/shop/checkout', { preserveScroll: true })" class="space-y-3">
                        <input v-model="checkout.customer_name" placeholder="Name" class="w-full rounded-lg border-gray-300" required />
                        <input v-model="checkout.customer_mobile" placeholder="Mobile" class="w-full rounded-lg border-gray-300" required />
                        <input v-model="checkout.customer_email" type="email" placeholder="Email" class="w-full rounded-lg border-gray-300" />
                        <textarea v-model="checkout.shipping_address" placeholder="Shipping Address" rows="3" class="w-full rounded-lg border-gray-300" required></textarea>
                        <div class="flex justify-between border-t pt-3 font-semibold"><span>Total</span><span class="text-saffron-600">₹{{ total }}</span></div>
                        <button class="btn-temple w-full" :disabled="checkout.processing">Place Order</button>
                    </form>
                </div>
            </div>
            <p v-else class="text-center text-gray-500">Cart is empty. <Link href="/shop" class="text-saffron-600 hover:underline">Browse shop</Link>.</p>
        </section>
    </AppLayout>
</template>
