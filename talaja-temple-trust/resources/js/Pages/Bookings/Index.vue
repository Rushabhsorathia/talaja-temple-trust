<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ roomTypes: Array, halls: Array, locale: String });

const tab = ref('room');
const availability = ref(null);
const loading = ref(false);

const roomForm = useForm({
    room_type_id: '', check_in: '', check_out: '', guests: 1,
    guest_name: '', guest_email: '', guest_mobile: '', payment_mode: 'pay_at_temple', note: '',
});
const hallForm = useForm({
    meeting_hall_id: '', event_date: '', start_time: '', end_time: '', attendees: 1,
    guest_name: '', guest_mobile: '', note: '',
});

const checkAvailability = async () => {
    loading.value = true;
    const res = await fetch(`/bookings/availability?check_in=${roomForm.check_in}&check_out=${roomForm.check_out}`);
    availability.value = await res.json();
    loading.value = false;
};
</script>

<template>
    <AppLayout :locale="locale">
        <Head><title>Bookings</title></Head>
        <section class="bg-temple-gradient py-16 text-center text-white">
            <h1 class="font-serif text-4xl font-bold">Accommodation & Halls</h1>
        </section>

        <section class="mx-auto max-w-4xl px-4 py-12">
            <div class="mb-6 flex justify-center gap-2">
                <button @click="tab='room'" class="rounded-full px-5 py-2 text-sm" :class="tab==='room'?'bg-saffron-600 text-white':'bg-saffron-50 text-saffron-700'">Rooms</button>
                <button @click="tab='hall'" class="rounded-full px-5 py-2 text-sm" :class="tab==='hall'?'bg-saffron-600 text-white':'bg-saffron-50 text-saffron-700'">Meeting Halls</button>
            </div>

            <!-- Rooms -->
            <div v-if="tab==='room'" class="space-y-6">
                <div class="card-temple">
                    <h3 class="mb-3 font-serif text-lg text-maroon-900">Check Availability</h3>
                    <div class="flex flex-wrap items-end gap-3">
                        <div><label class="block text-xs">Check-in</label><input type="date" v-model="roomForm.check_in" class="rounded-lg border-gray-300" /></div>
                        <div><label class="block text-xs">Check-out</label><input type="date" v-model="roomForm.check_out" class="rounded-lg border-gray-300" /></div>
                        <button @click="checkAvailability" :disabled="!roomForm.check_in || !roomForm.check_out || loading" class="btn-temple">Check</button>
                    </div>
                    <div v-if="availability" class="mt-4 space-y-2">
                        <p v-for="a in availability" :key="a.type_id" class="text-sm" :class="a.available_count>0 ? 'text-green-700' : 'text-red-600'">
                            {{ a.type_name }} — ₹{{ a.tariff }}/night × {{ a.nights }} = ₹{{ a.tariff * a.nights }} ({{ a.available_count }} available)
                        </p>
                    </div>
                </div>

                <form @submit.prevent="roomForm.post('/bookings/room', { preserveScroll: true })" class="card-temple space-y-3">
                    <h3 class="font-serif text-lg text-maroon-900">Book a Room</h3>
                    <select v-model="roomForm.room_type_id" class="w-full rounded-lg border-gray-300" required>
                        <option value="">Select room type</option>
                        <option v-for="r in roomTypes" :key="r.id" :value="r.id">{{ r.name }} — ₹{{ r.tariff }} (cap. {{ r.capacity }})</option>
                    </select>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <input type="date" v-model="roomForm.check_in" class="rounded-lg border-gray-300" required />
                        <input type="date" v-model="roomForm.check_out" class="rounded-lg border-gray-300" required />
                        <input v-model="roomForm.guests" type="number" min="1" placeholder="Guests" class="rounded-lg border-gray-300" required />
                        <select v-model="roomForm.payment_mode" class="rounded-lg border-gray-300">
                            <option value="pay_at_temple">Pay at Temple</option>
                            <option value="online">Pay Online</option>
                        </select>
                        <input v-model="roomForm.guest_name" placeholder="Name" class="rounded-lg border-gray-300" required />
                        <input v-model="roomForm.guest_mobile" placeholder="Mobile" class="rounded-lg border-gray-300" required />
                        <input v-model="roomForm.guest_email" type="email" placeholder="Email" class="rounded-lg border-gray-300 sm:col-span-2" />
                    </div>
                    <p v-if="roomForm.errors.room_type_id" class="text-sm text-red-600">{{ roomForm.errors.room_type_id }}</p>
                    <button class="btn-temple w-full" :disabled="roomForm.processing">Confirm Booking</button>
                </form>
            </div>

            <!-- Halls -->
            <form v-else @submit.prevent="hallForm.post('/bookings/hall', { preserveScroll: true })" class="card-temple space-y-3">
                <h3 class="font-serif text-lg text-maroon-900">Book a Meeting Hall</h3>
                <select v-model="hallForm.meeting_hall_id" class="w-full rounded-lg border-gray-300" required>
                    <option value="">Select hall</option>
                    <option v-for="h in halls" :key="h.id" :value="h.id">{{ h.name }} — ₹{{ h.tariff }} (cap. {{ h.capacity }})</option>
                </select>
                <div class="grid gap-3 sm:grid-cols-2">
                    <input type="date" v-model="hallForm.event_date" class="rounded-lg border-gray-300" required />
                    <input v-model="hallForm.attendees" type="number" min="1" placeholder="Attendees" class="rounded-lg border-gray-300" required />
                    <input type="time" v-model="hallForm.start_time" class="rounded-lg border-gray-300" required />
                    <input type="time" v-model="hallForm.end_time" class="rounded-lg border-gray-300" required />
                    <input v-model="hallForm.guest_name" placeholder="Name" class="rounded-lg border-gray-300" required />
                    <input v-model="hallForm.guest_mobile" placeholder="Mobile" class="rounded-lg border-gray-300" required />
                </div>
                <button class="btn-temple w-full" :disabled="hallForm.processing">Confirm Booking</button>
            </form>
        </section>
    </AppLayout>
</template>
