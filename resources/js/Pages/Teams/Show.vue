<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    team: Object,
    available_users: Array,
});

const memberForm = useForm({
    user_id: '',
    role: 'member',
});

const addMember = () => {
    memberForm.post(route('teams.members.add', props.team.id), {
        onSuccess: () => memberForm.reset(),
    });
};

const removeMember = (userId) => {
    if (confirm('Apakah Anda yakin ingin mengeluarkan anggota ini?')) {
        router.delete(route('teams.members.remove', { team: props.team.id, user: userId }));
    }
};
</script>

<template>
    <Head title="Manajemen Tim" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-6">
                <Link :href="route('teams.index')" class="p-3 bg-white border border-gray-100 text-gray-400 hover:text-telkom-red rounded-2xl shadow-sm transition-all group">
                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </Link>
                <div>
                    <h2 class="font-black text-4xl text-gray-900 tracking-tight lowercase truncate max-w-md">{{ team.name }}</h2>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1 italic">Pusat kendali dan administrasi tim</p>
                </div>
            </div>
            <div class="ml-auto">
                <Link :href="route('teams.edit', team.id)" class="px-6 py-3 bg-gray-50 text-gray-400 hover:text-gray-900 font-black text-[10px] rounded-xl border border-transparent hover:border-gray-100 transition-all uppercase tracking-widest">
                    Edit Profil Tim
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Team Overview -->
            <div class="lg:col-span-2 space-y-10">
                <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm p-12 md:p-16 space-y-12">
                     <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] italic leading-none">Visi & Misi Tim</h3>
                        <p class="text-gray-600 font-bold leading-relaxed text-lg">{{ team.description || 'Tim ini dibentuk untuk meningkatkan efisiensi kolaborasi digital di lingkungan perusahaan.' }}</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-10 pt-10 border-t border-gray-50">
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Team Leader</p>
                            <p class="text-xs font-black text-gray-900 lowercase italic">{{ team.owner.name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Total Anggota</p>
                            <p class="text-xs font-black text-gray-900 italic lowercase">{{ team.members.length }} Personel</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Dokumen Terkait</p>
                            <p class="text-xs font-black text-gray-900 italic lowercase">{{ team.documents?.length || 0 }} Berkas</p>
                        </div>
                    </div>
                </div>

                <!-- Member List -->
                <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-12 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] italic">Daftar Anggota Aktif</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div v-for="member in team.members" :key="member.id" class="px-12 py-8 flex items-center justify-between group hover:bg-gray-50/50 transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-xs font-black text-gray-400 uppercase group-hover:bg-telkom-red group-hover:text-white transition-all">
                                    {{ member.user.name.charAt(0) }}
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 lowercase">{{ member.user.name }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ member.role }}</p>
                                </div>
                            </div>
                            <button v-if="team.owner_id === $page.props.auth.user.id && member.user_id !== team.owner_id" @click="removeMember(member.user_id)" class="p-3 text-gray-300 hover:text-red-500 transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Management -->
            <div class="space-y-10">
                <div v-if="team.owner_id === $page.props.auth.user.id" class="bg-gray-900 rounded-[3.5rem] border border-black shadow-2xl p-10 text-white">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-10 italic text-center">Rekrut Anggota Baru</h3>
                    <form @submit.prevent="addMember" class="space-y-8">
                        <div class="space-y-2">
                            <select v-model="memberForm.user_id" required class="block w-full bg-white/5 border border-white/10 rounded-2xl h-14 px-6 text-xs font-bold focus:ring-telkom-red transition-all text-white cursor-pointer">
                                <option value="" class="bg-gray-900">PILIH KARYAWAN</option>
                                <option v-for="u in available_users" :key="u.id" :value="u.id" class="bg-gray-900">{{ u.name }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <select v-model="memberForm.role" required class="block w-full bg-white/5 border border-white/10 rounded-2xl h-14 px-6 text-xs font-bold focus:ring-telkom-red transition-all text-white cursor-pointer">
                                <option value="member" class="bg-gray-900">ANGGOTA (MEMBER)</option>
                                <option value="manager" class="bg-gray-900">PENGELOLA (MANAGER)</option>
                            </select>
                        </div>
                        <button type="submit" 
                            class="w-full py-5 bg-telkom-red hover:bg-white hover:text-telkom-red text-white text-[10px] font-black rounded-2xl shadow-xl shadow-red-900 transition-all uppercase tracking-widest"
                            :disabled="memberForm.processing || !memberForm.user_id">
                            TAMBAHKAN KE TIM
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm p-10">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8 italic">Metadata Tim</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="text-gray-400 uppercase tracking-widest">Dibuat Pada</span>
                            <span class="text-gray-900 lowercase italic">{{ team.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
