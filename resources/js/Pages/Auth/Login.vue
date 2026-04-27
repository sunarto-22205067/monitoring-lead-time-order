<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex bg-slate-950">
        <!-- Left Panel - Brand -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-red-700 via-red-600 to-red-800 relative overflow-hidden flex-col justify-between p-12">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-16">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl">Telkom DMS</span>
                </div>
                <h1 class="text-4xl font-black text-white leading-tight mb-4">
                    Sistem Monitoring<br>Proyek Jaringan
                </h1>
                <p class="text-red-100 text-lg font-medium leading-relaxed">
                    Platform terpadu untuk pemantauan progress ODP, port realisasi, dan analitik proyek secara real-time.
                </p>
            </div>
            <div class="relative grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5">
                    <p class="text-red-200 text-xs font-bold uppercase tracking-widest mb-1">Total Proyek</p>
                    <p class="text-white text-3xl font-black">—</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5">
                    <p class="text-red-200 text-xs font-bold uppercase tracking-widest mb-1">ODP Realisasi</p>
                    <p class="text-white text-3xl font-black">—</p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="lg:hidden flex items-center gap-3 mb-10 justify-center">
                    <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl">Telkom DMS</span>
                </div>

                <div v-if="status" class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-xl text-sm text-green-300">
                    {{ status }}
                </div>

                <h2 class="text-2xl font-black text-white mb-2">Masuk ke Sistem</h2>
                <p class="text-slate-400 text-sm mb-8">Gunakan kredensial akun Anda untuk melanjutkan.</p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                        <input v-model="form.email" type="email" autocomplete="username" required
                            class="w-full bg-slate-800/80 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"
                            placeholder="nama@telkom.co.id"/>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                        <input v-model="form.password" type="password" autocomplete="current-password" required
                            class="w-full bg-slate-800/80 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"
                            placeholder="••••••••"/>
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input v-model="form.remember" type="checkbox" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-red-600 focus:ring-red-500 focus:ring-offset-0"/>
                            <span class="text-sm text-slate-400">Ingat saya</span>
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-red-400 hover:text-red-300 font-semibold transition-colors">
                            Lupa password?
                        </Link>
                    </div>

                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white font-bold py-3.5 rounded-xl transition-all duration-200 text-sm shadow-lg shadow-red-900/30 hover:shadow-red-700/40 active:scale-[0.99]">
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Masuk ke Dashboard</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
