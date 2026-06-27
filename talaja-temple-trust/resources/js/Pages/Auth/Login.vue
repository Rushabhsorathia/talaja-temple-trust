<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Phone, Mail, User, KeyRound, ArrowRight, ShieldCheck } from '@lucide/vue';

const props = defineProps({
    status: String,
    loginPrompt: String,
    canResetPassword: { type: Boolean, default: true },
    presetMethod: { type: String, default: 'otp' },
    presetMode: { type: String, default: 'signin' },
});

// ---- method toggle: 'otp' | 'email' ----
const method = ref(props.presetMethod || 'otp');
const emailMode = ref(props.presetMode || 'signin');

// ---- OTP flow (mobile) ----
const otpStep = ref('mobile'); // 'mobile' | 'verify'
const otpForm = useForm({ mobile: '', otp: '', name: '' });
const otpStatus = ref('');
const otpError = ref('');

const sendOtp = () => {
    otpError.value = '';
    otpForm.post('/otp/send', {
        preserveScroll: true,
        onSuccess: () => { otpStep.value = 'verify'; otpStatus.value = 'OTP sent! Check the logs in dev mode.'; },
        onError: (e) => { otpError.value = e.mobile || 'Could not send OTP.'; },
    });
};
const verifyOtp = () => {
    otpError.value = '';
    otpForm.post('/otp/verify', {
        onError: (e) => { otpError.value = e.otp || 'Invalid or expired OTP.'; },
    });
};
const resendOtp = () => { otpStep.value = 'mobile'; otpStatus.value = ''; };

// ---- Email/password flow ----
const signinForm = useForm({ email: '', password: '', remember: false });
const signupForm = useForm({ name: '', email: '', password: '', password_confirmation: '' });

const submitSignin = () => signinForm.post('/login', { onFinish: () => signinForm.reset('password') });
const submitSignup = () => signupForm.post('/register', { onFinish: () => signupForm.reset('password', 'password_confirmation') });
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white lg:flex-row">
        <Head><title>{{ emailMode === 'signup' && method === 'email' ? 'Sign Up' : 'Login' }} — Talaja Temple Trust</title></Head>

        <!-- Left brand panel (desktop) -->
        <div class="relative hidden lg:block lg:w-1/2">
            <img src="/storage/hero/temple-1.jpg" alt="Temple" class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-br from-maroon-950/80 to-saffron-900/70"></div>
            <div class="relative z-10 flex h-full flex-col justify-between p-12 text-white">
                <Link href="/" class="flex items-center gap-3">
                    <img src="/storage/temple/logo.jpg" alt="Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/30" />
                    <div>
                        <p class="font-serif text-lg font-bold">Talaja Temple Trust</p>
                        <p class="text-xs text-saffron-300">|| Jay Mataji ||</p>
                    </div>
                </Link>
                <div>
                    <p class="font-serif text-sm text-saffron-300">Welcome back</p>
                    <h1 class="mt-2 font-serif text-4xl font-bold leading-tight">Connect with the divine</h1>
                    <p class="mt-4 max-w-md text-cream/80">Donate, book your stay, shop for prasad and receive blessings — all in one place. Your spiritual journey begins here.</p>
                    <div class="mt-8 flex gap-6">
                        <div><p class="font-serif text-2xl font-bold text-saffron-300">5L+</p><p class="text-xs text-cream/70">Devotees</p></div>
                        <div><p class="font-serif text-2xl font-bold text-saffron-300">24/7</p><p class="text-xs text-cream/70">Live Darshan</p></div>
                        <div><p class="font-serif text-2xl font-bold text-saffron-300">100+</p><p class="text-xs text-cream/70">Years</p></div>
                    </div>
                </div>
                <Link href="/" class="text-sm text-cream/60 hover:text-white">← Back to home</Link>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <Link href="/" class="mb-6 flex items-center justify-center gap-3 lg:hidden">
                    <img src="/storage/temple/logo.jpg" alt="Logo" class="h-11 w-11 rounded-full object-cover ring-2 ring-saffron-200" />
                    <p class="font-serif text-base font-bold text-maroon-900">Talaja Temple Trust</p>
                </Link>

                <div v-if="loginPrompt" class="mb-5 rounded-xl bg-saffron-50 px-4 py-3 text-center text-sm text-saffron-700">🔐 {{ loginPrompt }}</div>

                <!-- Method tabs -->
                <div class="mb-6 grid grid-cols-2 gap-1 rounded-full bg-gray-100 p-1">
                    <button @click="method = 'otp'" class="flex items-center justify-center gap-2 rounded-full py-2 text-sm font-medium transition" :class="method === 'otp' ? 'bg-white text-saffron-700 shadow' : 'text-gray-500'">
                        <Phone :size="15" /> Mobile OTP
                    </button>
                    <button @click="method = 'email'" class="flex items-center justify-center gap-2 rounded-full py-2 text-sm font-medium transition" :class="method === 'email' ? 'bg-white text-saffron-700 shadow' : 'text-gray-500'">
                        <Mail :size="15" /> Email
                    </button>
                </div>

                <!-- ===================== OTP METHOD ===================== -->
                <div v-if="method === 'otp'">
                    <h2 class="font-serif text-2xl font-bold text-maroon-900">{{ otpStep === 'mobile' ? 'Login / Sign Up' : 'Verify OTP' }}</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ otpStep === 'mobile' ? 'Enter your mobile number — new devotees are registered automatically.' : `Enter the 6-digit code sent to ${otpForm.mobile}.` }}
                    </p>

                    <div v-if="otpStatus" class="mt-4 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-700">{{ otpStatus }}</div>
                    <div v-if="otpError" class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">{{ otpError }}</div>

                    <!-- Step 1: mobile -->
                    <form v-if="otpStep === 'mobile'" @submit.prevent="sendOtp" class="mt-5 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Mobile Number</label>
                            <div class="relative">
                                <Phone :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="otpForm.mobile" type="tel" pattern="[6-9][0-9]{9}" maxlength="10" placeholder="10-digit mobile number" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                            </div>
                        </div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full bg-saffron-500 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-saffron-600" :disabled="otpForm.processing">
                            Send OTP <ArrowRight :size="16" />
                        </button>
                    </form>

                    <!-- Step 2: verify -->
                    <form v-else @submit.prevent="verifyOtp" class="mt-5 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Name <span class="text-gray-400">(for new devotees)</span></label>
                            <div class="relative">
                                <User :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="otpForm.name" type="text" placeholder="Your full name" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" />
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Enter OTP</label>
                            <input v-model="otpForm.otp" inputmode="numeric" maxlength="6" placeholder="6-digit code" class="w-full rounded-xl border border-gray-200 py-3 px-4 text-center text-lg tracking-[0.5em] outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                        </div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full bg-saffron-500 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-saffron-600" :disabled="otpForm.processing">
                            <ShieldCheck :size="16" /> Verify & Continue
                        </button>
                        <div class="flex items-center justify-between text-sm">
                            <button type="button" @click="resendOtp" class="text-gray-500 hover:text-saffron-600">← Change number</button>
                            <button type="button" @click="sendOtp" class="font-medium text-saffron-600 hover:underline">Resend OTP</button>
                        </div>
                    </form>
                </div>

                <!-- ===================== EMAIL METHOD ===================== -->
                <div v-else>
                    <!-- sign in / sign up toggle -->
                    <div class="mb-6 grid grid-cols-2 gap-1 rounded-full bg-gray-100 p-1">
                        <button @click="emailMode = 'signin'" class="rounded-full py-1.5 text-sm font-medium transition" :class="emailMode === 'signin' ? 'bg-white text-saffron-700 shadow' : 'text-gray-500'">Sign In</button>
                        <button @click="emailMode = 'signup'" class="rounded-full py-1.5 text-sm font-medium transition" :class="emailMode === 'signup' ? 'bg-white text-saffron-700 shadow' : 'text-gray-500'">Sign Up</button>
                    </div>

                    <!-- Sign in -->
                    <form v-if="emailMode === 'signin'" @submit.prevent="submitSignin" class="space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                            <div class="relative">
                                <Mail :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="signinForm.email" type="email" placeholder="you@example.com" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <KeyRound :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="signinForm.password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-gray-600"><input type="checkbox" v-model="signinForm.remember" class="rounded text-saffron-500" /> Remember me</label>
                            <Link v-if="canResetPassword" href="/forgot-password" class="font-medium text-saffron-600 hover:underline">Forgot password?</Link>
                        </div>
                        <div v-if="Object.keys(signinForm.errors).length" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">{{ signinForm.errors.email || signinForm.errors.password }}</div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full bg-saffron-500 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-saffron-600" :disabled="signinForm.processing">Sign In <ArrowRight :size="16" /></button>
                    </form>

                    <!-- Sign up -->
                    <form v-else @submit.prevent="submitSignup" class="space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="relative">
                                <User :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="signupForm.name" type="text" placeholder="Your name" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                            <div class="relative">
                                <Mail :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input v-model="signupForm.email" type="email" placeholder="you@example.com" class="w-full rounded-xl border border-gray-200 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Password</label>
                            <input v-model="signupForm.password" type="password" placeholder="Min 8 characters" class="w-full rounded-xl border border-gray-200 py-3 px-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input v-model="signupForm.password_confirmation" type="password" placeholder="Re-enter password" class="w-full rounded-xl border border-gray-200 py-3 px-4 text-sm outline-none transition focus:border-saffron-400 focus:ring-2 focus:ring-saffron-100" required />
                        </div>
                        <div v-if="Object.keys(signupForm.errors).length" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">{{ signupForm.errors.email || signupForm.errors.password || Object.values(signupForm.errors)[0] }}</div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full bg-saffron-500 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-saffron-600" :disabled="signupForm.processing">Create Account <ArrowRight :size="16" /></button>
                    </form>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">By continuing you agree to the temple's terms & privacy policy.</p>
            </div>
        </div>
    </div>
</template>
