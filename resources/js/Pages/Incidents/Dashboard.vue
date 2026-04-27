<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    stats: Object,
});

const statusChart = ref(null);
const priorityChart = ref(null);

onMounted(() => {
    // Status Chart
    new Chart(statusChart.value, {
        type: 'doughnut',
        data: {
            labels: props.stats.by_status.map(s => s.status.toUpperCase()),
            datasets: [{
                data: props.stats.by_status.map(s => s.count),
                backgroundColor: ['#00428A', '#EE2E24', '#94A3B8', '#F1F5F9'],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '82%',
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#00428A',
                    titleFont: { family: 'Outfit', size: 11 },
                    bodyFont: { family: 'Inter', size: 10 },
                    padding: 12,
                    displayColors: false
                }
            }
        }
    });

    // Priority Chart
    new Chart(priorityChart.value, {
        type: 'bar',
        data: {
            labels: props.stats.by_priority.map(p => p.priority.toUpperCase()),
            datasets: [{
                data: props.stats.by_priority.map(p => p.count),
                backgroundColor: '#EE2E24',
                hoverBackgroundColor: '#00428A',
                borderRadius: 8,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { display: false } },
                x: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: 'bold', size: 9 }, color: '#94A3B8' } }
            }
        }
    });
});
</script>

<template>
    <Head title="Beranda Intelijen Keamanan" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 border-b-2 border-slate-200/50">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Intelijen Insiden</h2>
                    <p class="text-slate-500 font-bold text-[11px] mt-2 uppercase tracking-[0.2em] italic">Komando Operasional & Pengawasan Pelanggaran Taktis</p>
                </div>
                <Link :href="route('incidents.index')" class="bg-slate-900 border-2 border-slate-900 text-white hover:bg-white hover:text-slate-900 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Log Aktivitas Lengkap
                </Link>
            </div>
        </template>

        <div class="mt-8 space-y-10">
            <!-- Statistik Strategis -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div v-for="(val, label) in { total: 'Total Laporan', new: 'Tahap Awal', processing: 'Respon Taktis', critical: 'Laporan Kritis' }" :key="label" 
                    class="card-telkom p-8 group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-2 h-full bg-slate-900 transition-all duration-300 group-hover:bg-telkom-red"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ label === 'total' ? 'TOTAL SINYAL' : (label === 'new' ? 'FASE BARU' : (label === 'processing' ? 'DIPROSES' : 'KRITIKAL')) }}</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-900 tracking-tighter group-hover:text-red-600 transition-colors uppercase italic">{{ stats[label] }}</span>
                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Log</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Bento -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Distribusi Siklus Hidup -->
                <div class="lg:col-span-4 card-telkom p-10 flex flex-col items-center">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-10 w-full italic">Distribusi Siklus Hidup</h3>
                    <div class="relative w-44 h-44 mb-10">
                        <canvas ref="statusChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase leading-none">{{ stats.total }}</span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Sinyal</span>
                        </div>
                    </div>
                    <div class="w-full space-y-3">
                        <div v-for="s in stats.by_status" :key="s.status" class="flex justify-between items-center p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full" :class="s.status === 'new' ? 'bg-red-600' : 'bg-slate-900'"></div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ s.status === 'new' ? 'BARU' : (s.status === 'processing' ? 'PROSES' : s.status) }}</span>
                            </div>
                            <span class="font-black text-slate-900 text-xs">{{ s.count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pemetaan Kritikalitas -->
                <div class="lg:col-span-4 bg-telkom-header telkom-pattern p-10 rounded-2xl shadow-xl text-white relative overflow-hidden">
                    <h3 class="text-[11px] font-black text-white/50 uppercase tracking-widest mb-10 italic relative z-10">Pemetaan Kritikalitas</h3>
                    <div class="h-44 mb-10 relative z-10">
                        <canvas ref="priorityChart"></canvas>
                    </div>
                    <div class="space-y-5 relative z-10">
                        <div v-for="p in stats.by_priority" :key="p.priority" class="flex justify-between items-center border-b border-white/10 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2.5">
                                <div v-if="p.priority === 'critical'" class="w-2 h-2 bg-red-600 rounded-full animate-ping"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest" :class="p.priority === 'critical' ? 'text-red-400' : 'text-white/60'">{{ p.priority === 'critical' ? 'KRITIS' : (p.priority === 'high' ? 'TINGGI' : (p.priority === 'medium' ? 'SEDANG' : 'RENDAH')) }}</span>
                            </div>
                            <span class="text-2xl font-black tracking-tighter italic uppercase transition-all hover:text-red-400">{{ p.count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Feed Taktis Langsung -->
                <div class="lg:col-span-4 card-telkom p-10 flex flex-col">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-10 italic">Feed Taktis Langsung</h3>
                    <div class="space-y-5 flex-1 overflow-y-auto max-h-[420px] pr-2 custom-scrollbar">
                        <div v-for="incident in stats.recent" :key="incident.id" class="p-5 bg-slate-50 border border-slate-100 hover:border-slate-900 hover:bg-white rounded-2xl transition-all group cursor-pointer" @click="$inertia.visit(route('incidents.show', incident.id))">
                            <div class="flex justify-between items-start mb-3">
                                <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded text-[8px] font-black uppercase tracking-widest border border-red-100">{{ incident.priority === 'critical' ? 'KRITIS' : (incident.priority === 'high' ? 'TINGGI' : 'NORMAL') }}</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter tabular-nums">{{ incident.created_at }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-2 leading-tight group-hover:text-red-600 transition-colors uppercase tracking-tight">{{ incident.title }}</h4>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-white px-2 py-0.5 rounded border border-slate-100">{{ incident.work_unit }}</span>
                                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[10px] font-black uppercase shadow-sm group-hover:bg-red-600 transition-colors">
                                    {{ incident.creator.name.charAt(0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <Link :href="route('incidents.create')" class="mt-10 py-4 bg-telkom-header hover:bg-slate-900 text-white rounded-xl text-[10px] font-black text-center uppercase tracking-widest transition-all shadow-xl font-black">
                        Kirim Log Baru
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-slate-200 rounded-full hover:bg-slate-300;
}
</style>
