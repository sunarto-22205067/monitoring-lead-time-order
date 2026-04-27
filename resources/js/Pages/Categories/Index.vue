<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlideOver from '@/Components/SlideOver.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ActionDropdown from '@/Components/ActionDropdown.vue';

const props = defineProps({
    categories: Array,
});

const isCreateDrawerOpen = ref(false);
const isEditDrawerOpen = ref(false);
const isConfirmDeleteOpen = ref(false);
const categoryToDelete = ref(null);

const createForm = useForm({
    name: '',
    description: '',
});

const editForm = useForm({
    id: null,
    name: '',
    description: '',
});

const openCreateDrawer = () => {
    createForm.reset();
    isCreateDrawerOpen.value = true;
};

const openEditDrawer = (category) => {
    editForm.id = category.id;
    editForm.name = category.name;
    editForm.description = category.description || '';
    isEditDrawerOpen.value = true;
};

const submitCreate = () => {
    createForm.post(route('categories.store'), {
        onSuccess: () => {
            isCreateDrawerOpen.value = false;
            createForm.reset();
        },
    });
};

const submitUpdate = () => {
    editForm.put(route('categories.update', editForm.id), {
        onSuccess: () => {
            isEditDrawerOpen.value = false;
        },
    });
};

const confirmDelete = (category) => {
    categoryToDelete.value = category;
    isConfirmDeleteOpen.value = true;
};

const submitDelete = () => {
    if (categoryToDelete.value) {
        router.delete(route('categories.destroy', categoryToDelete.value.id), {
            onSuccess: () => {
                isConfirmDeleteOpen.value = false;
                categoryToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Klasifikasi Perusahaan" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 border-b-2 border-slate-200/50">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight leading-none uppercase">Klasifikasi Dokumen</h2>
                    <p class="text-slate-500 font-bold text-[11px] mt-2 uppercase tracking-wider">Kelola struktur & hierarki dokumen perusahaan</p>
                </div>
                <button @click="openCreateDrawer" class="bg-slate-900 border-2 border-slate-900 text-white hover:bg-white hover:text-slate-900 px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Tambah Kategori
                </button>
            </div>
        </template>

            <div class="card-telkom px-8 py-6">
                <!-- Pencarian & Kontrol -->
                <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-4 flex items-center shadow-sm">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <input type="text" placeholder="Cari kategori..." class="w-full bg-slate-50 border-none rounded-xl pl-11 pr-4 py-3 text-sm font-medium focus:ring-2 focus:ring-slate-900/5 transition-all">
                </div>
            </div>

            <div class="card-telkom">
                <div class="">
                    <table class="w-full text-left table-telkom">
                        <thead>
                            <tr>
                                <th>Identifikasi Kategori</th>
                                <th>Deskripsi Singkat</th>
                                <th class="text-center">Jumlah Aset</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="category in categories" :key="category.id" class="hover:bg-slate-50/50 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-telkom-header flex items-center justify-center text-white shadow-sm font-bold text-xs uppercase">
                                            {{ category.name.substring(0, 2) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ category.name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs text-slate-500 line-clamp-1 font-medium">{{ category.description || 'Tidak ada data deskriptif yang disediakan.' }}</p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg border border-slate-200">
                                        {{ category.documents_count }} ASET
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <ActionDropdown>
                                        <button @click="openEditDrawer(category)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                            Ubah Definisi
                                        </button>
                                        <div class="border-t border-slate-50"></div>
                                        <button @click="confirmDelete(category)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                            Hapus Blok
                                        </button>
                                    </ActionDropdown>
                                </td>
                            </tr>
                            <tr v-if="categories.length === 0">
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mb-6 opacity-40">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-width="2"/></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-900 uppercase">Tidak Ada Kategori Ditemukan</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Definisikan kategori baru untuk mengatur perpustakaan Anda</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Drawer -->
        <SlideOver :show="isCreateDrawerOpen" title="Kategori Operasional Baru" @close="isCreateDrawerOpen = false">
            <template #description>Tambahkan tingkatan klasifikasi baru untuk struktur organisasi dokumen.</template>
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Kategori</label>
                    <input v-model="createForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Keuangan, Hukum, Teknis..." required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deskripsi Strategis</label>
                    <textarea v-model="createForm.description" rows="4" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Tujuan atau ruang lingkup kategori ini..."></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="createForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-slate-900 transition-all">
                        Simpan Kategori Inti
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Edit Drawer -->
        <SlideOver :show="isEditDrawerOpen" title="Sesuaikan Klasifikasi" @close="isEditDrawerOpen = false">
            <template #description>Ubah konvensi penamaan atau ruang lingkup kategori yang ada.</template>
            <form @submit.prevent="submitUpdate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identitas Kategori</label>
                    <input v-model="editForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Narasi Diperbarui</label>
                    <textarea v-model="editForm.description" rows="6" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none"></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="editForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-slate-900 transition-all">
                        Selesaikan Pembaruan Kategori
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Delete Confirmation -->
        <ConfirmModal 
            :show="isConfirmDeleteOpen" 
            title="Hapus Tingkatan Klasifikasi?"
            message="Ini akan menghapus kategori secara permanen. Pastikan tidak ada aset yang saat ini terhubung ke tingkatan ini."
            @close="isConfirmDeleteOpen = false"
            @confirm="submitDelete"
        />
    </AppLayout>
</template>
