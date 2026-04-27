<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Konfirmasi Password" />

    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6 selection:bg-telkom-red selection:text-white font-outfit">
        <div class="mb-12 flex flex-col items-center group">
            <div class="w-16 h-16 bg-gray-900 rounded-[1.5rem] shadow-2xl shadow-gray-200 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-telkom-red transition-all duration-500">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tighter lowercase">Telkom DMS</h1>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.4em] mt-2 italic text-center">Konfirmasi Keamanan</p>
        </div>

        <div class="w-full max-w-md">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-white overflow-hidden text-center">
                <div class="p-10 md:p-14">
                    <p class="text-xs text-gray-400 font-bold mb-10 italic leading-relaxed">
                        Area ini sangat sensitif. Silakan masukkan kata sandi Anda untuk melanjutkan.
                    </p>

                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] ml-2 text-left block">Password</label>
                            <input v-model="form.password" type="password" required autofocus class="block w-full bg-gray-50 border-none rounded-[1.2rem] h-14 px-6 text-sm font-bold focus:ring-telkom-red transition-all" placeholder="••••••••">
                            <div v-if="form.errors.password" class="text-red-500 text-[9px] font-bold uppercase mt-1 ml-2 text-left">{{ form.errors.password }}</div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full py-5 bg-gray-900 hover:bg-black text-white text-[10px] font-black rounded-3xl shadow-2xl shadow-gray-300 transition-all transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 uppercase tracking-widest"
                                :disabled="form.processing">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
