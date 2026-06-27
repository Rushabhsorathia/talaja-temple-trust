<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
defineProps({ products: Array, locale: String, flash: Object });
const addForm = useForm({});
const addToCart = (id) => addForm.post(`/shop/add`, { data: { product_id: id, qty: 1 }, preserveScroll: true });
</script>
<template>
    <AppLayout :locale="locale">
        <Head><title>Shop</title></Head>
        <section class="bg-temple-gradient py-12 text-center text-white">
            <h1 class="font-serif text-3xl font-bold">Temple Shop</h1>
        </section>
        <section class="mx-auto max-w-6xl px-4 py-12">
            <div v-if="flash?.success" class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ flash.success }}</div>
            <div class="mb-4 text-right"><Link href="/shop/cart" class="btn-temple !px-4 !py-2 text-sm">View Cart</Link></div>
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-4">
                <div v-for="p in products" :key="p.id" class="card-temple text-center">
                    <div class="mx-auto mb-3 aspect-square w-full rounded-xl bg-saffron-100"></div>
                    <h3 class="font-serif text-maroon-900">{{ p.name }}</h3>
                    <p class="font-semibold text-saffron-600">₹{{ p.price }}</p>
                    <button @click="addToCart(p.id)" class="mt-2 rounded-full bg-saffron-600 px-4 py-1.5 text-sm text-white" :disabled="addForm.processing">Add to Cart</button>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
