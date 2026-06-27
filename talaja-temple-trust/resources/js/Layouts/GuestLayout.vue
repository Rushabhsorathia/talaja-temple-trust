<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-white pt-6 sm:justify-center sm:pt-0">
        <!-- Logo -->
        <div class="mb-6">
            <Link href="/" class="flex flex-col items-center gap-2">
                <img src="/storage/temple/logo.jpg" alt="Talaja Temple Trust" class="h-20 w-20 rounded-full object-cover ring-4 ring-saffron-200" />
                <p class="font-serif text-lg font-bold text-maroon-900">Talaja Temple Trust</p>
                <p class="text-[10px] tracking-widest text-saffron-500 uppercase">|| Jay Mataji ||</p>
            </Link>
        </div>

        <!-- Auth card -->
        <div class="w-full sm:max-w-md">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Saffron header bar -->
                <div class="h-1.5 w-full bg-gradient-to-r from-saffron-400 via-saffron-500 to-saffron-600"></div>
                <div class="px-6 py-8">
                    <slot />
                </div>
            </div>

            <!-- Back to site -->
            <p class="mt-6 text-center text-xs text-gray-400">
                <Link href="/" class="text-saffron-600 hover:underline">Back to temple site</Link>
            </p>
        </div>
    </div>
</template>