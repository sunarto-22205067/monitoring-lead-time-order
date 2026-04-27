<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlideOver from '@/Components/SlideOver.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ActionDropdown from '@/Components/ActionDropdown.vue';

const props = defineProps({
    documents: Object,
    categories: Array,
    teams: Array,
});

// --- Manajemen State ---
const isCreateDrawerOpen = ref(false);
const isEditDrawerOpen = ref(false);
const isConfirmDeleteOpen = ref(false);
const docToDelete = ref(null);

// --- Logika Form ---
const createForm = useForm({
    title: '',
    file: null,
    category_id: '',
    team_id: '',
    description: '',
});

const editForm = useForm({
    id: null,
    title: '',
    category_id: '',
    team_id: '',
    description: '',
});

// --- Aksi ---
const openCreateDrawer = () => {
    createForm.reset();
    isCreateDrawerOpen.value = true;
};

const openEditDrawer = (doc) => {
    editForm.id = doc.id;
    editForm.title = doc.title;
    editForm.category_id = doc.category_id || '';
    editForm.team_id = doc.team_id || '';
    editForm.description = doc.description || '';
    isEditDrawerOpen.value = true;
};

const handleFileUpload = (e) => {
    createForm.file = e.target.files[0];
};

const submitCreate = () => {
    createForm.post(route('documents.store'), {
        onSuccess: () => {
            isCreateDrawerOpen.value = false;
            createForm.reset();
        },
    });
};

const submitUpdate = () => {
    editForm.put(route('documents.update', editForm.id), {
        onSuccess: () => {
            isEditDrawerOpen.value = false;
        },
    });
};

const confirmDelete = (doc) => {
    docToDelete.value = doc;
    isConfirmDeleteOpen.value = true;
};

const submitDelete = () => {
    if (docToDelete.value) {
        router.delete(route('documents.destroy', docToDelete.value.id), {
            onSuccess: () => {
                isConfirmDeleteOpen.value = false;
                docToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Pusat Arsip Digital" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-slate-900 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800 tracking-tight leading-none uppercase">Arsip Digital</h2>
                        <p class="text-slate-500 font-bold text-[11px] mt-1.5 uppercase tracking-wider">Kelola repositori & koleksi dokumen perusahaan</p>
                    </div>
                </div>
                <button @click="openCreateDrawer" class="bg-slate-900 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Unggah Dokumen
                </button>
            </div>
        </template>

        <div class="mt-10 animate-in fade-in slide-in-from-bottom-5 duration-700">
            <!-- Kontainer Tabel -->
            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm">
                <div class="">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Identitas Dokumen</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Klasifikasi</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center whitespace-nowrap">Versi Aset</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="doc in documents.data" :key="doc.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300 shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <Link :href="route('documents.show', doc.id)" class="block text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors truncate max-w-sm">{{ doc.title }}</Link>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ doc.team ? doc.team.name : 'Penyimpanan Pribadi' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-[9px] font-bold rounded-lg uppercase tracking-widest shadow-sm">
                                        {{ doc.category?.name || 'UMUM' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex items-center px-2.5 py-1 bg-slate-900 text-white rounded-lg text-[10px] font-bold tabular-nums shadow-md">
                                        REV-{{ doc.current_version?.version_number || 1 }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <ActionDropdown>
                                        <Link :href="route('documents.show', doc.id)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Periksa Arsip
                                        </Link>
                                        <button @click="openEditDrawer(doc)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                            Ubah Metadata
                                        </button>
                                        <div class="border-t border-slate-50"></div>
                                        <button @click="confirmDelete(doc)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                            Hapus Aset
                                        </button>
                                    </ActionDropdown>
                                </td>
                            </tr>
                            <tr v-if="documents.data.length === 0">
                                <td colspan="4" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mb-8 opacity-40">
                                            <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5"/></svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-slate-900 uppercase tracking-tight">Repositori Kosong</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-3">Unggah dokumen awal diperlukan untuk mengisi data</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Navigasi Halaman -->
                <div v-if="documents.links && documents.links.length > 3" class="px-8 py-6 border-t border-slate-100 flex justify-center bg-slate-50/30">
                    <nav class="flex gap-1.5">
                        <Link v-for="(link, k) in documents.links" :key="k"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all"
                            :class="{
                                'bg-slate-900 text-white shadow-xl': link.active,
                                'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50': !link.active,
                                'opacity-40 cursor-not-allowed': !link.url
                            }"
                        />
                    </nav>
                </div>
            </div>
        </div>

        <!-- Panel Tambah (Unggah) -->
        <SlideOver :show="isCreateDrawerOpen" title="Penerimaan Aset Perusahaan" @close="isCreateDrawerOpen = false">
            <template #description>Digitalkan dan arsipkan dokumen perusahaan ke dalam repositori aman.</template>
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Judul Dokumen</label>
                    <input v-model="createForm.title" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Laporan Tahunan 2024" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aset Biner (PDF/XLS/DOC)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <input type="file" @change="handleFileUpload" class="block w-full bg-slate-50 border-slate-200 border-dashed border-2 rounded-xl h-24 pl-12 pr-4 text-[10px] font-bold text-slate-900 file:hidden cursor-pointer flex items-center pt-8" required>
                        <div v-if="!createForm.file" class="absolute left-12 top-10 pointer-events-none text-[10px] font-bold text-slate-400 uppercase">Pilih atau Seret dokumen ke sini</div>
                        <div v-if="createForm.file" class="absolute left-12 top-10 pointer-events-none text-[10px] font-bold text-emerald-600 uppercase tracking-widest">SIAP: {{ createForm.file.name.substring(0, 30) }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klasifikasi</label>
                        <select v-model="createForm.category_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                            <option value="">PILIH KATEGORI</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Penugasan Unit</label>
                        <select v-model="createForm.team_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                            <option value="">PRIBADI / PERSONAL</option>
                            <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deskripsi Teknis</label>
                    <textarea v-model="createForm.description" rows="4" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Berikan konteks atau ringkasan..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" :disabled="createForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-slate-900 transition-all">
                        Finalisasi Arsip Aset
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Panel Ubah (Metadata) -->
        <SlideOver :show="isEditDrawerOpen" title="Sesuaikan Metadata" @close="isEditDrawerOpen = false">
            <template #description>Perbaiki identifikasi dokumen atau detail penugasan.</template>
            <form @submit.prevent="submitUpdate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identitas Dokumen</label>
                    <input v-model="editForm.title" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klasifikasi</label>
                        <select v-model="editForm.category_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Penugasan Unit</label>
                        <select v-model="editForm.team_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                            <option value="">PRIBADI / PERSONAL</option>
                            <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan Revisi</label>
                    <textarea v-model="editForm.description" rows="6" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none"></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" :disabled="editForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-slate-900 transition-all">
                        Simpan Pembaruan Identitas
                    </button>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest text-center mt-6">Operasi ini hanya memengaruhi data deskriptif, bukan aset biner.</p>
                </div>
            </form>
        </SlideOver>

        <!-- Konfirmasi Hapus -->
        <ConfirmModal 
            :show="isConfirmDeleteOpen" 
            title="Hapus Aset Perusahaan?"
            message="Semua versi dan riwayat yang terkait dengan dokumen ini akan dihapus secara permanen dari infrastruktur."
            @close="isConfirmDeleteOpen = false"
            @confirm="submitDelete"
        />
    </AppLayout>
</template>

<style scoped>
.animate-in {
    animation-fill-mode: both;
}
</style>
