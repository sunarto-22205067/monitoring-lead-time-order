<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    token: String,
    email: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <div class="min-h-screen flex items-center justify-center bg-slate-950 p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-900/40">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-white mb-2">Password Baru</h2>
                <p class="text-slate-400 text-sm">Buat password baru yang kuat untuk akun Anda.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                        <input v-model="form.email" type="email" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"/>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password Baru</label>
                        <input v-model="form.password" type="password" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"
                            placeholder="Min. 8 karakter"/>
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                        <input v-model="form.password_confirmation" type="password" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all"
                            placeholder="Ulangi password baru"/>
                        <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-red-400">{{ form.errors.password_confirmation }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-red-600 hover:bg-red-500 disabled:opacity-50 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else">Simpan Password Baru</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
