<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import debounce from 'lodash/debounce';

const props = defineProps({ regions: Object, filters: Object, types: Array });

const search = ref(props.filters.search || '');
const typeFilter = ref(props.filters.type || '');
const showForm = ref(false);
const editingRegion = ref(null);

const applyFilters = debounce(() => {
    router.get(route('regions.index'), { search: search.value, type: typeFilter.value }, { preserveState: true, replace: true });
}, 400);
watch([search, typeFilter], applyFilters);

const form = useForm({ type: 'Witel', name: '', parent_name: '' });

function openCreate() { editingRegion.value = null; form.reset(); form.type = 'Witel'; showForm.value = true; }
function openEdit(region) {
    editingRegion.value = region;
    form.type = region.type; form.name = region.name; form.parent_name = region.parent_name || '';
    showForm.value = true;
}
function closeForm() { showForm.value = false; form.reset(); }
function submit() {
    if (editingRegion.value) {
        form.put(route('regions.update', editingRegion.value.id), { onSuccess: closeForm });
    } else {
        form.post(route('regions.store'), { onSuccess: closeForm });
    }
}
function deleteRegion(r) {
    if (confirm(`Hapus region "${r.name}"?`)) router.delete(route('regions.destroy', r.id));
}

function typeColor(type) {
    return { Witel: 'bg-blue-100 text-blue-700', Datel: 'bg-green-100 text-green-700', STO: 'bg-orange-100 text-orange-700' }[type] || 'bg-slate-100 text-slate-600';
}
</script>

<template>
    <Head title="Region" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Region</h1>
                    <p class="text-sm text-slate-500">Kelola data Witel, Datel, dan STO</p>
                </div>
                <button @click="openCreate" class="btn-primary text-xs px-3 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Region
                </button>
            </div>
        </template>

        <div class="space-y-4">
            <div class="card p-4 flex flex-wrap gap-3">
                <input v-model="search" type="text" placeholder="Cari region..." class="form-input max-w-sm text-sm px-4 py-2.5"/>
                <select v-model="typeFilter" class="form-input w-40 text-sm px-4 py-2.5">
                    <option value="">Semua Tipe</option>
                    <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
                </select>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header">Tipe</th>
                            <th class="table-header">Nama</th>
                            <th class="table-header">Parent</th>
                            <th class="table-header text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-if="!regions.data.length">
                            <td colspan="4" class="py-12 text-center text-slate-400 text-sm">Tidak ada data region</td>
                        </tr>
                        <tr v-for="region in regions.data" :key="region.id" class="hover:bg-slate-50">
                            <td class="table-cell">
                                <span :class="['text-xs font-bold px-2.5 py-1 rounded-lg', typeColor(region.type)]">{{ region.type }}</span>
                            </td>
                            <td class="table-cell font-semibold text-slate-900">{{ region.name }}</td>
                            <td class="table-cell text-slate-500">{{ region.parent_name || '—' }}</td>
                            <td class="table-cell text-right">
                                <div class="flex justify-end gap-1">
                                    <button @click="openEdit(region)" class="p-1.5 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteRegion(region)" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="regions.last_page > 1" class="p-4 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-xs text-slate-500">{{ regions.from }}–{{ regions.to }} dari {{ regions.total }}</p>
                    <div class="flex gap-1">
                        <a v-for="link in regions.links" :key="link.label" :href="link.url || '#'"
                            :class="['px-3 py-1.5 text-xs font-medium rounded-lg', link.active ? 'bg-red-600 text-white' : link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 cursor-default']"
                            v-html="link.label"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <transition name="fade">
            <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-900">{{ editingRegion ? 'Edit Region' : 'Tambah Region' }}</h3>
                    </div>
                    <form @submit.prevent="submit" class="p-6 space-y-4">
                        <div>
                            <label class="form-label">Tipe *</label>
                            <select v-model="form.type" class="form-input">
                                <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Nama *</label>
                            <input v-model="form.name" type="text" class="form-input" required/>
                            <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="form-label">Parent (Induk)</label>
                            <input v-model="form.parent_name" type="text" class="form-input" placeholder="Nama Witel/Datel induk..."/>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="closeForm" class="btn-secondary">Batal</button>
                            <button type="submit" :disabled="form.processing" class="btn-primary">
                                {{ editingRegion ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>
