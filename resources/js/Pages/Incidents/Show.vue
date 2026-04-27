<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    report: Object,
    available_users: Array,
});

const page = usePage();
const user = page.props.auth.user;

const statusForm = useForm({
    status: props.report.status,
    comment: '',
});

const assignForm = useForm({
    user_id: props.report.assigned_to || '',
});

const updateStatus = () => {
    statusForm.post(route('incidents.update-status', props.report.id), {
        onSuccess: () => statusForm.reset('comment'),
    });
};

const assignUser = () => {
    assignForm.post(route('incidents.assign', props.report.id));
};

const priorityConfig = {
    'low': { label: 'RUTIN', class: 'bg-slate-50 text-slate-500 border-slate-100' },
    'medium': { label: 'SEDANG', class: 'bg-blue-50 text-blue-600 border-blue-100' },
    'high': { label: 'TINGGI', class: 'bg-orange-50 text-orange-600 border-orange-100' },
    'critical': { label: 'KRITIS', class: 'bg-red-50 text-red-600 border-red-100 animate-pulse font-black' }
};

const statusConfig = {
    'new': { label: 'INISIAL', class: 'bg-indigo-50 text-indigo-700 border-indigo-100' },
    'processing': { label: 'PROSES', class: 'bg-amber-50 text-amber-700 border-amber-100' },
    'waiting_follow_up': { label: 'MENUNGGU FOLLOW-UP', class: 'bg-purple-50 text-purple-700 border-purple-100' },
    'completed': { label: 'SELESAI', class: 'bg-emerald-50 text-emerald-700 border-emerald-100' },
    'closed': { label: 'ARSIP', class: 'bg-slate-100 text-slate-600 border-slate-200' }
};
</script>

<template>
    <Head title="Analisis Forensik Insiden" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                     <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">#INC-{{ String(report.id).padStart(5,'0') }}</h2>
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-lg border shadow-sm tracking-widest uppercase"
                            :class="priorityConfig[report.priority].class">
                            {{ priorityConfig[report.priority].label }}
                        </span>
                         <span class="px-2.5 py-1 text-[9px] font-black rounded-lg border shadow-sm tracking-widest uppercase transition-all"
                            :class="statusConfig[report.status].class">
                            {{ statusConfig[report.status].label }}
                        </span>
                    </div>
                    <p class="text-slate-500 font-bold text-[11px] uppercase tracking-[0.1em] italic">{{ report.title }}</p>
                </div>
                <div>
                    <Link :href="route('incidents.index')" class="bg-white text-slate-600 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-red-600 border border-slate-200 transition-all shadow-sm">
                        &larr; Kembali ke Daftar
                    </Link>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10 animate-in fade-in slide-in-from-bottom-5 duration-700">
            <!-- Kolom Kiri: Detail & Kronologi -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Detail Insiden -->
                <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                     <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Deskripsi & Kronologi Kejadian</h3>
                    </div>
                    <div class="p-8 text-slate-700 text-sm font-semibold leading-relaxed bg-white">
                         {{ report.description }}
                    </div>
                    <div class="border-t border-slate-50 p-0 bg-white">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-50">
                             <div class="px-8 py-6">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Deteksi</dt>
                                <dd class="text-xs font-black text-slate-900 uppercase tracking-tight tabular-nums">
                                    {{ report.happened_at }}
                                </dd>
                            </div>
                            <div class="px-8 py-6">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi / Poin Deteksi IP</dt>
                                <dd class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                    {{ report.location }}
                                </dd>
                            </div>
                            <div class="px-8 py-6">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Kerja Penanggung Jawab</dt>
                                <dd class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                    {{ report.work_unit }}
                                </dd>
                            </div>
                            <div class="px-8 py-6">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Otoritas Pelapor</dt>
                                <dd class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                    {{ report.creator.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Lampiran -->
                <div v-if="report.attachments?.length" class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Bukti Forensik & Aset Biner</h3>
                    </div>
                    <ul role="list" class="divide-y divide-slate-50">
                         <li v-for="file in report.attachments" :key="file.id" class="px-8 py-5 flex items-center justify-between text-xs group hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-600 transition-all">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <span class="font-black text-slate-600 uppercase tracking-tighter">{{ file.file_name }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                <a :href="'/storage/' + file.file_path" target="_blank" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-600 transition-all shadow-sm">
                                    Unduh Dokumen
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Audit Log / Timeline -->
                <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Jejak Audit & Koordinasi Operasional</h3>
                    </div>
                    <div class="flow-root p-8">
                        <ul role="list" class="-mb-8">
                            <li v-for="(log, logIdx) in report.logs" :key="log.id">
                                <div class="relative pb-8">
                                    <span v-if="logIdx !== report.logs.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-100" aria-hidden="true"></span>
                                    <div class="relative flex space-x-5">
                                        <div>
                                            <span class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center ring-8 ring-white shadow-lg">
                                                 <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs font-black text-slate-600 uppercase tracking-tight">
                                                    <span class="text-slate-900">{{ log.user.name }}</span> <span class="text-slate-400 font-bold mx-1">/</span> {{ log.action }}
                                                </p>
                                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-tighter tabular-nums">
                                                    {{ log.created_at }}
                                                </div>
                                            </div>
                                            <div v-if="log.from_status || log.to_status" class="flex items-center gap-2 mt-2">
                                                <span class="px-2 py-0.5 text-[8px] font-black rounded bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-widest">{{ log.from_status }}</span>
                                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                <span class="px-2 py-0.5 text-[8px] font-black rounded bg-red-50 text-red-600 border border-red-100 uppercase tracking-widest">{{ log.to_status }}</span>
                                            </div>
                                             <div v-if="log.comment" class="mt-3 text-xs font-semibold text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 italic">
                                                "{{ log.comment }}"
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Aksi Admin -->
            <div class="space-y-8">
                <!-- Manajemen Status -->
                <div class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Pembaruan Resolusi</h3>
                    </div>
                    <div class="p-8">
                        <form @submit.prevent="updateStatus" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kondisi Operasional Baru</label>
                                <select v-model="statusForm.status" class="block w-full bg-slate-50 border-slate-200 rounded-xl h-12 px-4 text-[10px] font-black uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all cursor-pointer">
                                    <option value="processing">PROSES / ANALISIS</option>
                                    <option value="waiting_follow_up">MENUNGGU FOLLOW-UP</option>
                                    <option value="completed">SELESAI / TERATASI</option>
                                    <option value="closed">ARSIP / DITUTUP</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Mitigasi / Progress</label>
                                <textarea v-model="statusForm.comment" rows="4" class="block w-full bg-slate-50 border-slate-200 rounded-2xl p-4 text-[11px] font-semibold text-slate-900 focus:ring-slate-900/10 focus:border-slate-900 transition-all resize-none" placeholder="Tuliskan tindakan teknis yang diambil..."></textarea>
                            </div>
                            <button type="submit" class="bg-slate-900 text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-black hover:bg-red-600 transition-all" :disabled="statusForm.processing">
                                Perbarui Status Strategis
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Penugasan -->
                <div v-if="user.roles.some(r => r.name === 'Admin' || r.name === 'Manager')" class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm">
                     <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Penugasan Personel</h3>
                    </div>
                    <div class="p-8">
                        <form @submit.prevent="assignUser" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Otoritas Penanggung Jawab</label>
                                <select v-model="assignForm.user_id" class="block w-full bg-slate-50 border-slate-200 rounded-xl h-12 px-4 text-[10px] font-black uppercase tracking-widest focus:ring-slate-900/10 focus:border-slate-900 transition-all cursor-pointer">
                                    <option value="">Pilih Personel Operasional</option>
                                    <option v-for="u in available_users" :key="u.id" :value="u.id">{{ u.name }}</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-red-600 text-white w-full py-4 rounded-xl shadow-xl disabled:opacity-50 uppercase tracking-[0.2em] text-[10px] font-black hover:bg-slate-900 transition-all" :disabled="assignForm.processing">
                                Delegasikan Tugas
                            </button>
                        </form>

                        <div v-if="report.assignee" class="mt-8 pt-6 border-t border-slate-50">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4 italic text-center">Personel Penanggung Jawab Saat Ini</p>
                            <div class="bg-slate-50 p-4 rounded-2xl flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-md">
                                    <span class="text-sm font-black uppercase">{{ report.assignee.name.charAt(0) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ report.assignee.name }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Aktif Bertugas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
