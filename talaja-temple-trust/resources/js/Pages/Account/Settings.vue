<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Compass, User } from '@lucide/vue';

const props = defineProps({ guideMode: Boolean, locale: String, flash: Object });

const form = useForm({ guide_mode: props.guideMode });

const toggle = () => {
    form.guide_mode = !form.guide_mode;
    form.put('/account/settings', { preserveScroll: true });
};
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Account Settings</title></Head>
        <section class="mx-auto max-w-2xl px-4 py-12">
            <Link href="/dashboard" class="mb-6 inline-flex items-center gap-1 text-sm text-saffron-600 hover:underline">← Back to dashboard</Link>
            <h1 class="font-serif text-3xl font-bold text-maroon-900">Account Settings</h1>
            <p class="mt-1 text-gray-500">Manage your preferences.</p>

            <div v-if="flash?.success" class="mt-6 rounded-xl bg-green-50 p-3 text-sm text-green-700">{{ flash.success }}</div>

            <!-- Guide Mode -->
            <div class="mt-8 rounded-2xl border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-saffron-50 text-saffron-600">
                        <Compass :size="22" />
                    </div>
                    <div class="flex-1">
                        <h2 class="font-serif text-lg font-semibold text-maroon-900">Guide Mode</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Enable an interactive guided tour that highlights key features of the portal —
                            how to donate, book a room, shop and watch live darshan. Ideal for first-time visitors.
                        </p>
                        <button type="button" @click="toggle" class="mt-4 flex items-center gap-3">
                            <span class="relative inline-flex h-6 w-11 items-center rounded-full transition" :class="form.guide_mode ? 'bg-saffron-500' : 'bg-gray-300'">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition" :class="form.guide_mode ? 'translate-x-5' : 'translate-x-0.5'"></span>
                            </span>
                            <span class="text-sm font-medium" :class="form.guide_mode ? 'text-saffron-700' : 'text-gray-600'">
                                {{ form.guide_mode ? 'ON — tour will appear on the site' : 'OFF' }}
                            </span>
                        </button>
                        <p v-if="form.recentlySuccessful" class="mt-2 text-xs text-green-600">Saved.</p>
                    </div>
                </div>
            </div>

            <!-- Profile shortcut -->
            <Link href="/profile" class="mt-4 flex items-center justify-between rounded-2xl border border-gray-200 p-6 transition hover:border-saffron-200 hover:bg-saffron-50/40">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-600"><User :size="20" /></div>
                    <div>
                        <h2 class="font-serif text-lg font-semibold text-maroon-900">Edit Profile</h2>
                        <p class="text-sm text-gray-500">Update your name, mobile, PAN and address.</p>
                    </div>
                </div>
                <span class="text-saffron-600">→</span>
            </Link>
        </section>
    </AppLayout>
</template>
