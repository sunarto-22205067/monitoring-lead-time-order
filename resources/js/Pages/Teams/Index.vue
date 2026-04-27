<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlideOver from '@/Components/SlideOver.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ActionDropdown from '@/Components/ActionDropdown.vue';

const props = defineProps({
    teams: Array,
});

// --- Manajemen State ---
const isCreateDrawerOpen = ref(false);
const isEditDrawerOpen = ref(false);
const isConfirmDeleteOpen = ref(false);
const teamToDelete = ref(null);

// --- Logika Form ---
const createForm = useForm({
    name: '',
    description: '',
});

const editForm = useForm({
    id: null,
    name: '',
    description: '',
});

// --- Aksi ---
const openCreateDrawer = () => {
    createForm.reset();
    isCreateDrawerOpen.value = true;
};

const openEditDrawer = (team) => {
    editForm.id = team.id;
    editForm.name = team.name;
    editForm.description = team.description || '';
    isEditDrawerOpen.value = true;
};

const submitCreate = () => {
    createForm.post(route('teams.store'), {
        onSuccess: () => {
            isCreateDrawerOpen.value = false;
            createForm.reset();
        },
    });
};

const submitUpdate = () => {
    editForm.put(route('teams.update', editForm.id), {
        onSuccess: () => {
            isEditDrawerOpen.value = false;
        },
    });
};

const confirmDelete = (team) => {
    teamToDelete.value = team;
    isConfirmDeleteOpen.value = true;
};

const submitDelete = () => {
    if (teamToDelete.value) {
        router.delete(route('teams.destroy', teamToDelete.value.id), {
            onSuccess: () => {
                isConfirmDeleteOpen.value = false;
                teamToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Kolaborasi Unit Kerja Perusahaan" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-slate-900 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Unit Organisasi</h2>
                        <p class="text-slate-500 font-bold text-[11px] mt-1.5 uppercase tracking-[0.2em] italic">Kelola tim kerja & sinergi proyek perusahaan</p>
                    </div>
                </div>
                <button @click="openCreateDrawer" class="bg-slate-900 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Bentuk Unit Baru
                </button>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10 animate-in fade-in slide-in-from-bottom-5 duration-700">
            <div v-for="team in teams" :key="team.id" class="bg-white border border-slate-200 p-8 rounded-[2rem] shadow-sm hover:shadow-xl transition-all duration-500 group relative overflow-hidden flex flex-col justify-between min-h-[340px]">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-slate-50 rounded-full group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:bg-red-600 transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4" stroke-width="2"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        
                        <div class="flex items-center gap-2">
                             <span v-if="team.owner_id === $page.props.auth.user.id" class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-[0.15em] rounded-lg border border-slate-200">Koordinator</span>
                             
                             <ActionDropdown>
                                <Link :href="route('teams.show', team.id)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Periksa Unit
                                </Link>
                                <button v-if="team.owner_id === $page.props.auth.user.id" @click="openEditDrawer(team)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                    Sesuaikan Nama
                                </button>
                                <div class="border-t border-slate-50"></div>
                                <button v-if="team.owner_id === $page.props.auth.user.id" @click="confirmDelete(team)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-all w-full text-left uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                    Bubarkan Unit
                                </button>
                             </ActionDropdown>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 mb-2 truncate uppercase tracking-tight group-hover:text-red-600 transition-colors">{{ team.name }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold mb-8 line-clamp-3 italic uppercase tracking-[0.1em] h-12 leading-relaxed">{{ team.description || 'Unit kolaborasi perusahaan untuk pelaksanaan proyek strategis dan visibilitas bersama.' }}</p>
                </div>
                
                <div class="flex items-center justify-between pt-8 border-t border-slate-100 relative z-10 mt-auto">
                    <div class="flex -space-x-4">
                        <div v-for="(member, idx) in team.members.slice(0, 4)" :key="idx" class="w-10 h-10 rounded-xl border-4 border-white bg-slate-900 flex items-center justify-center text-[10px] font-black text-white uppercase shadow-sm overflow-hidden" :title="member.user.name">
                            {{ member.user.name.charAt(0) }}
                        </div>
                        <div v-if="team.members.length > 4" class="w-10 h-10 rounded-xl border-4 border-white bg-slate-200 flex items-center justify-center text-[10px] font-black text-slate-600 shadow-sm">
                            +{{ team.members.length - 4 }}
                        </div>
                    </div>
                    
                    <Link :href="route('teams.show', team.id)" class="px-5 py-2.5 bg-slate-900 text-[10px] font-black text-white hover:bg-red-600 rounded-xl uppercase tracking-widest transition-all shadow-xl shadow-slate-100 group-hover:shadow-red-100">
                        Lihat Unit
                    </Link>
                </div>
            </div>

            <div v-if="!teams.length" class="lg:col-span-3 text-center py-40 border-2 border-dashed border-slate-200 rounded-[3rem]">
                <div class="flex flex-col items-center opacity-30">
                    <svg class="w-20 h-20 text-slate-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="1.5"/></svg>
                    <p class="text-slate-500 font-black uppercase text-xs tracking-[0.4em] italic leading-relaxed">Tidak ada unit operasional yang terdeteksi.</p>
                </div>
            </div>
        </div>

        <!-- Panel Tambah -->
        <SlideOver :show="isCreateDrawerOpen" title="Bentuk Unit Strategis" @close="isCreateDrawerOpen = false">
            <template #description>Resmikan departemen organisasi baru atau tim proyek.</template>
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Identitas Unit Kerja</label>
                    <input v-model="createForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Divisi Infrastruktur Barat..." required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lingkup Operasional</label>
                    <textarea v-model="createForm.description" rows="4" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Tujuan dan cakupan departemen..."></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="createForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-black hover:bg-slate-900 transition-all">
                        Inisialisasi Unit Organisasi
                    </button>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest text-center mt-6 italic">Pembuat akan ditetapkan sebagai koordinator administrasi untuk unit ini.</p>
                </div>
            </form>
        </SlideOver>

        <!-- Panel Ubah -->
        <SlideOver :show="isEditDrawerOpen" title="Penyelarasan Unit Operasional" @close="isEditDrawerOpen = false">
            <template #description>Perbarui identitas unit atau detail lingkup untuk departemen saat ini.</template>
            <form @submit.prevent="submitUpdate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identitas Unit</label>
                    <input v-model="editForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Narasi Operasional</label>
                    <textarea v-model="editForm.description" rows="6" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none"></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="editForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-black hover:bg-slate-900 transition-all">
                        Simpan Pembaruan Penyelarasan
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Konfirmasi Hapus -->
        <ConfirmModal 
            :show="isConfirmDeleteOpen" 
            title="Bubarkan Unit Perusahaan?"
            message="Tindakan ini akan secara permanen membubarkan unit organisasi, memutuskan semua hubungan anggota dan aset."
            @close="isConfirmDeleteOpen = false"
            @confirm="submitDelete"
        />
    </AppLayout>
</template>
