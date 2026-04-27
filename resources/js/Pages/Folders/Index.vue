<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlideOver from '@/Components/SlideOver.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ActionDropdown from '@/Components/ActionDropdown.vue';

const props = defineProps({
    folders: Array,
    categories: Array,
});

const isCreateDrawerOpen = ref(false);
const isEditDrawerOpen = ref(false);
const isConfirmDeleteOpen = ref(false);
const folderToDelete = ref(null);

const createForm = useForm({
    name: '',
    category_id: '',
    description: '',
});

const editForm = useForm({
    id: null,
    name: '',
    category_id: '',
    description: '',
});

const openCreateDrawer = () => {
    createForm.reset();
    isCreateDrawerOpen.value = true;
};

const openEditDrawer = (folder) => {
    editForm.id = folder.id;
    editForm.name = folder.name;
    editForm.category_id = folder.category_id;
    editForm.description = folder.description || '';
    isEditDrawerOpen.value = true;
};

const submitCreate = () => {
    createForm.post(route('folders.store'), {
        onSuccess: () => {
            isCreateDrawerOpen.value = false;
            createForm.reset();
        },
    });
};

const submitUpdate = () => {
    editForm.put(route('folders.update', editForm.id), {
        onSuccess: () => {
            isEditDrawerOpen.value = false;
        },
    });
};

const confirmDelete = (folder) => {
    folderToDelete.value = folder;
    isConfirmDeleteOpen.value = true;
};

const submitDelete = () => {
    if (folderToDelete.value) {
        router.delete(route('folders.destroy', folderToDelete.value.id), {
            onSuccess: () => {
                isConfirmDeleteOpen.value = false;
                folderToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Enterprise Directory Management" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-slate-900 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Folder Management</h2>
                        <p class="text-slate-500 font-bold text-[11px] mt-1.5 uppercase tracking-[0.2em] italic">Architectural directory structure for digital assets</p>
                    </div>
                </div>
                <button @click="openCreateDrawer" class="btn-primary-telkom flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    New Folder Node
                </button>
            </div>
        </template>

        <div class="mt-10 animate-in fade-in slide-in-from-bottom-5 duration-700">
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Folder Identity</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Operational Category</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center whitespace-nowrap">Inventory Count</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Operational</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 italic-links">
                            <tr v-for="folder in folders" :key="folder.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-100 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors uppercase tracking-tight">{{ folder.name }}</span>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 line-clamp-1 italic max-w-xs">{{ folder.description || 'Enterprise directory node.' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex px-3 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-md">
                                        {{ folder.category?.name || 'UNCATEGORIZED' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-black tabular-nums border border-slate-200 shadow-sm">
                                        {{ folder.documents_count }} <span class="text-[8px] ml-1.5 text-slate-400">FILES</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <ActionDropdown>
                                        <button @click="openEditDrawer(folder)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                                            Adjust Node
                                        </button>
                                        <div class="border-t border-slate-50"></div>
                                        <button @click="confirmDelete(folder)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                            Purge Directory
                                        </button>
                                    </ActionDropdown>
                                </td>
                            </tr>
                            <tr v-if="folders.length === 0">
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mb-6 opacity-40">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" stroke-width="2"/></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase">Directory Tree is Empty</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">Construct new folder nodes to map your repository</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Drawer -->
        <SlideOver :show="isCreateDrawerOpen" title="Construct New Directory Node" @close="isCreateDrawerOpen = false">
            <template #description>Initialize a new structural endpoint for project asset placement.</template>
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Node Identification</label>
                    <input v-model="createForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="e.g. Q1_TECHNICAL_REPORTS" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Classification Mapping</label>
                    <select v-model="createForm.category_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-black uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                        <option value="">SELECT TARGET CLASSIFICATION</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name.toUpperCase() }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Node Narrative</label>
                    <textarea v-model="createForm.description" rows="4" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Contextual or hierarchy notes..."></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="createForm.processing" class="btn-primary-telkom w-full !py-4 shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px]">
                        Initialize Directory Node
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Edit Drawer -->
        <SlideOver :show="isEditDrawerOpen" title="Adjust Directory Parameters" @close="isEditDrawerOpen = false">
            <template #description>Reconfigure naming or classification mapping for the existing node.</template>
            <form @submit.prevent="submitUpdate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Node Identity</label>
                    <input v-model="editForm.name" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Update Classification</label>
                    <select v-model="editForm.category_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-black uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all" required>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name.toUpperCase() }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Revised Narrative</label>
                    <textarea v-model="editForm.description" rows="6" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none"></textarea>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="editForm.processing" class="btn-primary-telkom w-full !py-4 shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px]">
                        Finalize Node Realignment
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Delete Confirmation -->
        <ConfirmModal 
            :show="isConfirmDeleteOpen" 
            title="Purge Directory Link?"
            message="This will permanently disconnect this folder node. Operational integrity requires that the folder is empty before deletion."
            @close="isConfirmDeleteOpen = false"
            @confirm="submitDelete"
        />
    </AppLayout>
</template>
