<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Detail User" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-6">
                <Link :href="route('users.index')" class="p-3 bg-white border border-gray-100 text-gray-400 hover:text-telkom-red rounded-2xl shadow-sm transition-all group">
                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </Link>
                <div>
                    <h2 class="font-black text-4xl text-gray-900 tracking-tight lowercase">Profil Pengguna</h2>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1 italic">Tinjauan otoritas dan aktivitas akun</p>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- User Profile Card -->
                <div class="md:col-span-1 bg-white rounded-[3rem] border border-gray-100 shadow-sm p-12 flex flex-col items-center text-center group">
                    <div class="w-32 h-32 rounded-full bg-red-50 text-telkom-red flex items-center justify-center text-4xl font-black uppercase mb-8 group-hover:scale-110 transition-transform duration-500">
                        {{ user.name.charAt(0) }}
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-1 lowercase">{{ user.name }}</h3>
                    <p class="text-xs text-gray-400 font-bold mb-10 lowercase">{{ user.email }}</p>
                    
                    <div class="w-full space-y-3">
                        <div class="p-5 bg-gray-50 rounded-[1.5rem] flex justify-between items-center">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Otoritas</span>
                            <span v-for="role in user.roles" :key="role.id" class="px-3 py-1 bg-gray-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest">{{ role.name }}</span>
                        </div>
                    </div>

                    <Link :href="route('users.edit', user.id)" class="w-full mt-8 py-4 bg-gray-50 hover:bg-gray-900 hover:text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">
                        Edit Profil
                    </Link>
                </div>

                <!-- Detailed Account Info -->
                <div class="md:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-sm p-12 space-y-12">
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-10 italic">Spesifikasi Akun</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">Unique Identifier</p>
                                <p class="text-sm font-bold text-gray-900 uppercase tracking-tighter">#USR-{{ String(user.id).padStart(5, '0') }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">Terhubung Sejak</p>
                                <p class="text-sm font-bold text-gray-900 uppercase italic">{{ user.created_at }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">Pembaruan Terakhir</p>
                                <p class="text-sm font-bold text-gray-900 uppercase italic">{{ user.updated_at }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-gray-50">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 italic">Keanggotaan Tim</h3>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="team in user.teams" :key="team.id" class="px-5 py-2.5 bg-gray-50 rounded-xl text-xs font-bold text-gray-600 border border-transparent hover:border-gray-200 transition-all">
                                {{ team.name }}
                            </div>
                            <p v-if="!user.teams?.length" class="text-xs text-gray-300 font-bold uppercase italic">Belum terdaftar di tim manapun</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
