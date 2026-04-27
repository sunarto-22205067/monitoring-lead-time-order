<script setup>
import { ref, watch } from 'vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlideOver from '@/Components/SlideOver.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ActionDropdown from '@/Components/ActionDropdown.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    reports: Object,
    filters: Object,
});

// --- Manajemen State ---
const status = ref(props.filters.status || 'all');
const priority = ref(props.filters.priority || 'all');
const isCreateDrawerOpen = ref(false);
const isConfirmDeleteOpen = ref(false);
const reportToDelete = ref(null);

// --- Logika Form ---
const createForm = useForm({
    title: '',
    description: '',
    happened_at: new Date().toISOString().slice(0, 16),
    location: '',
    work_unit: '',
    priority: 'medium',
    attachments: [],
});

const handleFileUpload = (e) => {
    createForm.attachments = Array.from(e.target.files);
};

const openCreateDrawer = () => {
    createForm.reset();
    isCreateDrawerOpen.value = true;
};

const submitCreate = () => {
    createForm.post(route('incidents.store'), {
        onSuccess: () => {
            isCreateDrawerOpen.value = false;
            createForm.reset();
        },
    });
};

const confirmDelete = (report) => {
    reportToDelete.value = report;
    isConfirmDeleteOpen.value = true;
};

const submitDelete = () => {
    if (reportToDelete.value) {
        router.delete(route('incidents.destroy', reportToDelete.value.id), {
            onSuccess: () => {
                isConfirmDeleteOpen.value = false;
                reportToDelete.value = null;
            },
        });
    }
};

const statusConfig = {
    'new': { label: 'INISIAL', class: 'bg-indigo-50 text-indigo-700 border-indigo-100' },
    'processing': { label: 'PROSES', class: 'bg-amber-50 text-amber-700 border-amber-100' },
    'completed': { label: 'SELESAI', class: 'bg-emerald-50 text-emerald-700 border-emerald-100' },
    'closed': { label: 'ARSIP', class: 'bg-slate-100 text-slate-600 border-slate-200 opacity-60' }
};

const priorityConfig = {
    'low': { label: 'RUTIN', class: 'bg-slate-50 text-slate-500 border-slate-100' },
    'medium': { label: 'DITINGKATKAN', class: 'bg-blue-50 text-blue-600 border-blue-100' },
    'high': { label: 'RISIKO TINGGI', class: 'bg-orange-50 text-orange-600 border-orange-100' },
    'critical': { label: 'KRITIS', class: 'bg-red-50 text-red-600 border-red-100 animate-pulse font-bold' }
};

watch([status, priority], debounce(() => {
    router.get(route('incidents.index'), { 
        status: status.value, 
        priority: priority.value 
    }, { preserveState: true, replace: true });
}, 300));
</script>

<template>
    <Head title="Manajemen Insiden Strategis" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-slate-900 rounded-full"></div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight leading-none uppercase">Insiden Keamanan</h2>
                        <p class="text-slate-500 font-bold text-[10px] sm:text-[11px] mt-1.5 uppercase tracking-wider">Mitigasi Risiko & Intelijen Keamanan Perusahaan</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a :href="route('incidents.export')" class="p-3 bg-white text-slate-400 hover:text-red-600 border border-slate-200 rounded-xl transition-all shadow-sm hover:shadow-md active:scale-95 duration-200" title="Ekspor Laporan Operasional">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                    <button @click="openCreateDrawer" class="bg-slate-900 text-white px-6 py-3.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg flex items-center justify-center gap-2 active:scale-95 duration-200 flex-1 sm:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Laporkan Pelanggaran
                    </button>
                </div>
            </div>
        </template>

        <div class="mt-8 space-y-6 sm:space-y-8 animate-in fade-in slide-in-from-bottom-5 duration-700">
            <!-- Seksi Filter -->
            <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-3xl shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center gap-4 sm:gap-6">
                <div class="flex flex-col gap-2 flex-1 min-w-[200px]">
                    <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Status Siklus Hidup</span>
                    <select v-model="status" class="block w-full bg-slate-50 border-slate-200 rounded-xl h-11 px-4 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all cursor-pointer active:scale-[0.99] duration-200">
                        <option value="all">SEMUA STATUS OPERASIONAL</option>
                        <option v-for="(label, val) in {new: 'BARU', processing: 'PROSES', completed: 'SELESAI', closed: 'DITUTUP'}" :key="val" :value="val">{{ label }}</option>
                    </select>
                </div>
                <div class="flex flex-col gap-2 flex-1 min-w-[200px]">
                    <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Prioritas Eskalasi</span>
                    <select v-model="priority" class="block w-full bg-slate-50 border-slate-200 rounded-xl h-11 px-4 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all cursor-pointer active:scale-[0.99] duration-200">
                        <option value="all">SELURUH TINGKAT KRITIS</option>
                        <option v-for="(label, val) in {low: 'RENDAH', medium: 'SEDANG', high: 'TINGGI', critical: 'KRITIS'}" :key="val" :value="val">{{ label }}</option>
                    </select>
                </div>
            </div>

            <!-- Seksi Tabel -->
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-left min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 sm:px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Kode Insiden</th>
                                <th class="px-6 sm:px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Intelijen Singkat</th>
                                <th class="px-6 sm:px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center whitespace-nowrap">Tingkat Bahaya</th>
                                <th class="px-6 sm:px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center whitespace-nowrap">Kondisi Operasional</th>
                                <th class="px-6 sm:px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="report in reports.data" :key="report.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 sm:px-8 py-6 whitespace-nowrap">
                                    <span class="text-[11px] font-bold text-slate-400 group-hover:text-red-600 transition-colors tabular-nums tracking-tighter bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 shadow-sm">#INC-{{ String(report.id).padStart(5,'0') }}</span>
                                </td>
                                <td class="px-6 sm:px-8 py-6">
                                    <div class="flex flex-col min-w-[280px]">
                                        <Link :href="route('incidents.show', report.id)" class="text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors uppercase tracking-tight truncate max-w-[250px] sm:max-w-sm">{{ report.title }}</Link>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ report.creator.name }}</span>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate max-w-[150px]">{{ report.work_unit }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 sm:px-8 py-6 text-center">
                                    <span v-if="report.priority" class="inline-flex px-3 py-1 text-[9px] font-bold rounded-lg border shadow-sm tracking-widest uppercase"
                                        :class="priorityConfig[report.priority].class">
                                        {{ priorityConfig[report.priority].label }}
                                    </span>
                                </td>
                                <td class="px-6 sm:px-8 py-6 text-center">
                                    <span v-if="report.status" class="inline-flex px-3 py-1 text-[9px] font-bold rounded-lg border shadow-sm tracking-widest uppercase transition-all"
                                        :class="statusConfig[report.status].class">
                                        {{ statusConfig[report.status].label }}
                                    </span>
                                </td>
                                <td class="px-6 sm:px-8 py-6 text-right whitespace-nowrap">
                                    <ActionDropdown>
                                        <Link :href="route('incidents.show', report.id)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Analisis Mendalam
                                        </Link>
                                        <div class="border-t border-slate-100"></div>
                                        <button @click="confirmDelete(report)" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-all w-full text-left uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                            Hapus Log Operasional
                                        </button>
                                    </ActionDropdown>
                                </td>
                            </tr>
                            <tr v-if="reports.data.length === 0">
                                <td colspan="5" class="px-6 sm:px-8 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mb-8 opacity-40">
                                            <svg class="w-12 h-12 sm:w-14 sm:h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5"/></svg>
                                        </div>
                                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 uppercase tracking-tight">Data Insiden Kosong</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-3">Tetap waspada; tidak ada pelanggaran keamanan kritis yang terdeteksi saat ini</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Navigasi Halaman -->
                <div v-if="reports.links && reports.links.length > 3" class="px-4 py-6 border-t border-slate-100 flex justify-center bg-slate-50/30">
                    <nav class="flex flex-wrap items-center justify-center gap-2">
                        <Link v-for="(link, k) in reports.links" :key="k"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="px-3 sm:px-4 py-2 rounded-xl text-[9px] sm:text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 duration-200"
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

        <!-- Panel Tambah (Laporan) -->
        <SlideOver :show="isCreateDrawerOpen" title="Pelaporan Pelanggaran Darurat" @close="isCreateDrawerOpen = false" maxWidth="2xl">
            <template #description>Catat peristiwa keamanan baru atau kegagalan sistem untuk respons administratif.</template>
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subjek Narasi Insiden</label>
                    <input v-model="createForm.title" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Kegagalan Node Autentikasi Kritis" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Deteksi</label>
                        <input v-model="createForm.happened_at" type="datetime-local" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-bold text-slate-900" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klasifikasi Bahaya</label>
                        <select v-model="createForm.priority" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-[10px] font-bold uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all">
                            <option value="low">RENDAH / RUTIN</option>
                            <option value="medium">SEDANG / DITINGKATKAN</option>
                            <option value="high">TINGGI / RISIKO TINGGI</option>
                            <option value="critical">KRITIS / KRITIS</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi Deteksi / IP</label>
                        <input v-model="createForm.location" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Pusat Data A - Rak Utama" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unit Penanggung Jawab</label>
                        <input v-model="createForm.work_unit" type="text" class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 h-12 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all" placeholder="misal: Operasi Keamanan Siber" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kronologi Teknis</label>
                    <textarea v-model="createForm.description" rows="5" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-sm font-semibold focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Urutan kronologis peristiwa yang rinci..." required></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bukti Forensik (Opsional)</label>
                    <input type="file" multiple @change="handleFileUpload" class="block w-full bg-slate-50 border-slate-200 border-dashed border-2 rounded-xl h-14 px-4 py-2.5 text-[10px] font-bold text-slate-400 file:hidden cursor-pointer flex items-center">
                    <p v-if="createForm.attachments.length" class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest ml-1">{{ createForm.attachments.length }} paket bukti disiapkan</p>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="createForm.processing" class="bg-telkom-header text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-bold hover:bg-slate-900 transition-all">
                        Transmisikan Laporan Strategis
                    </button>
                </div>
            </form>
        </SlideOver>

        <!-- Konfirmasi Hapus -->
        <ConfirmModal 
            :show="isConfirmDeleteOpen" 
            title="Hapus Rekaman Operasional?"
            message="Semua data forensik dan log kronologis yang terkait dengan insiden ini akan dihapus secara permanen."
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
