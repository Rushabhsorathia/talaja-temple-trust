<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Phone, Mail } from '@lucide/vue';

defineProps({ temple: Object, locale: String, flash: Object });

const form = useForm({
    type: 'feedback',
    name: '',
    email: '',
    mobile: '',
    category: '',
    rating: '',
    message: '',
});

const submit = () => form.post('/contact-us', { preserveScroll: true, onSuccess: () => form.reset() });
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Contact Us</title></Head>
        <section class="bg-gradient-to-b from-saffron-50 to-white py-20 text-center">
            <h1 class="font-serif text-4xl font-bold text-maroon-900">Contact Us</h1>
        </section>
        <section class="mx-auto grid max-w-5xl gap-10 px-4 py-16 md:grid-cols-2">
            <div>
                <h2 class="mb-4 font-serif text-2xl text-maroon-900">Reach Us</h2>
                <p class="text-gray-600" v-html="temple?.address || ''"></p>
                <p class="mt-2 flex items-center gap-2 text-gray-600" v-if="temple?.phone"><Phone class="h-4 w-4 text-saffron-600" /> {{ temple.phone }}</p>
                <p class="flex items-center gap-2 text-gray-600" v-if="temple?.email"><Mail class="h-4 w-4 text-saffron-600" /> {{ temple.email }}</p>
                <iframe v-if="temple?.map_embed" :src="temple.map_embed" class="mt-4 h-56 w-full rounded-xl border-0" loading="lazy"></iframe>
            </div>
            <div class="card-temple">
                <h2 class="mb-4 font-serif text-2xl text-maroon-900">Send a Message</h2>
                <div v-if="flash?.success" class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ flash.success }}</div>
                <form @submit.prevent="submit" class="space-y-3">
                    <select v-model="form.type" class="w-full rounded-lg border-gray-300">
                        <option value="feedback">Feedback</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="complaint">Complaint</option>
                    </select>
                    <input v-model="form.name" placeholder="Name" class="w-full rounded-lg border-gray-300" />
                    <input v-model="form.email" type="email" placeholder="Email" class="w-full rounded-lg border-gray-300" />
                    <input v-model="form.mobile" placeholder="Mobile" class="w-full rounded-lg border-gray-300" />
                    <textarea v-model="form.message" placeholder="Message" rows="4" required class="w-full rounded-lg border-gray-300"></textarea>
                    <p v-if="form.errors.message" class="text-sm text-red-600">{{ form.errors.message }}</p>
                    <button type="submit" class="btn-temple w-full" :disabled="form.processing">Submit</button>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
