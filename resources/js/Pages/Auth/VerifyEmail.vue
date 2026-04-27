<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Verifikasi Email" />

    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6 selection:bg-telkom-red selection:text-white font-outfit">
        <div class="mb-12 flex flex-col items-center group">
            <div class="w-16 h-16 bg-gray-900 rounded-[1.5rem] shadow-2xl shadow-gray-200 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-telkom-red transition-all duration-500">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tighter lowercase">Telkom DMS</h1>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.4em] mt-2 italic text-center">Verifikasi Identitas Digital</p>
        </div>

        <div class="w-full max-w-md">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-white overflow-hidden text-center">
                <div class="p-10 md:p-14">
                    <p class="text-xs text-gray-400 font-bold mb-10 italic leading-relaxed">
                        Terima kasih telah bergabung! Silakan verifikasi email Anda dengan mengeklik tautan yang baru saja kami kirimkan.
                    </p>

                    <div v-if="verificationLinkSent" class="mb-10 p-4 bg-green-50 rounded-2xl text-green-600 text-[10px] font-black uppercase tracking-widest">
                        Tautan verifikasi baru telah dikirim!
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <button type="submit" 
                            class="w-full py-5 bg-gray-900 hover:bg-black text-white text-[10px] font-black rounded-3xl shadow-2xl shadow-gray-300 transition-all transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 uppercase tracking-widest"
                            :disabled="form.processing">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>
                
                <div class="bg-gray-50/50 p-8 flex justify-center gap-10 border-t border-gray-50">
                    <Link :href="route('logout')" method="post" as="button" class="text-[9px] font-black text-gray-400 hover:text-telkom-red uppercase tracking-widest transition-all">Keluar</Link>
                </div>
            </div>
        </div>
    </div>
</template>
