<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ status: String });

const form = useForm({ email: '' });

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Lupa Password" />
    <div class="min-h-screen flex items-center justify-center bg-slate-950 p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-900/40">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-white mb-2">Reset Password</h2>
                <p class="text-slate-400 text-sm">Masukkan email Anda dan kami akan mengirimkan tautan reset.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
                <div v-if="status" class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-xl text-sm text-green-300">{{ status }}</div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input v-model="form.email" type="email" required autocomplete="username"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"
                            placeholder="nama@telkom.co.id"/>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
                        <span v-if="form.processing">Mengirim...</span>
                        <span v-else>Kirim Tautan Reset</span>
                    </button>
                </form>
                <div class="mt-6 text-center">
                    <Link :href="route('login')" class="text-sm text-slate-400 hover:text-white transition-colors">
                        ← Kembali ke Login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
