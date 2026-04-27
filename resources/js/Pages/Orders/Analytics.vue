<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    byRegional: Array,
    byDesignType: Array,
    byMitra: Array,
    monthly: Array,
    summary: Object,
});

function fNum(v) { return Number(v || 0).toLocaleString('id-ID'); }
function fCur(v) { return 'Rp ' + Number(v || 0).toLocaleString('id-ID'); }
</script>

<template>
    <Head title="Analytics" />
    <AppLayout>
        <template #header>
            <div>
                <h1 class="text-lg font-bold text-slate-900">Analytics</h1>
                <p class="text-sm text-slate-500">Ringkasan analitik proyek jaringan</p>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Summary KPI -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="stat-card text-center">
                    <p class="text-2xl font-black text-slate-900">{{ fNum(summary.total) }}</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Total Order</p>
                </div>
                <div class="stat-card text-center">
                    <p class="text-2xl font-black text-green-700">{{ fNum(summary.active) }}</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Aktif</p>
                </div>
                <div class="stat-card text-center">
                    <p class="text-2xl font-black text-blue-700">{{ fNum(summary.completed) }}</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Selesai</p>
                </div>
                <div class="stat-card text-center">
                    <p class="text-2xl font-black text-red-700">{{ fNum(summary.overdue) }}</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Overdue</p>
                </div>
                <div class="stat-card text-center">
                    <p class="text-xl font-black text-slate-900">{{ summary.odpRate }}%</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">ODP Rate</p>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Ringkasan Keuangan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 font-medium mb-1">Total PO Value</p>
                        <p class="text-lg font-black text-slate-900">{{ fCur(summary.totalPo) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-green-600 font-medium mb-1">Total GR Value</p>
                        <p class="text-lg font-black text-green-700">{{ fCur(summary.totalGr) }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-blue-600 font-medium mb-1">Total IR Value</p>
                        <p class="text-lg font-black text-blue-700">{{ fCur(summary.totalIr) }}</p>
                    </div>
                </div>
            </div>

            <!-- Regional Table -->
            <div class="card overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-900">Breakdown per Regional</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header">Regional</th>
                                <th class="table-header">Status</th>
                                <th class="table-header text-right">Jumlah</th>
                                <th class="table-header text-right">ODP Plan</th>
                                <th class="table-header text-right">ODP Real</th>
                                <th class="table-header text-right">Port Real</th>
                                <th class="table-header">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="r in byRegional" :key="r.regional + r.status_project" class="hover:bg-slate-50">
                                <td class="table-cell font-semibold">{{ r.regional || '—' }}</td>
                                <td class="table-cell">
                                    <span :class="{ active: 'badge-active', completed: 'badge-completed', overdue: 'badge-overdue' }[r.status_project] || 'badge-active'">
                                        {{ r.status_project }}
                                    </span>
                                </td>
                                <td class="table-cell text-right font-bold">{{ fNum(r.total) }}</td>
                                <td class="table-cell text-right">{{ fNum(r.odp_plan) }}</td>
                                <td class="table-cell text-right text-red-700 font-semibold">{{ fNum(r.odp_real) }}</td>
                                <td class="table-cell text-right text-blue-700 font-semibold">{{ fNum(r.port_real) }}</td>
                                <td class="table-cell w-32">
                                    <div class="flex items-center gap-2">
                                        <div class="progress-bar flex-1">
                                            <div class="progress-fill bg-red-500"
                                                :style="`width:${r.odp_plan > 0 ? Math.min((r.odp_real/r.odp_plan)*100,100) : 0}%`"></div>
                                        </div>
                                        <span class="text-xs font-semibold w-8 text-right">
                                            {{ r.odp_plan > 0 ? Math.round((r.odp_real/r.odp_plan)*100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- By Design Type -->
                <div class="card overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h3 class="text-sm font-bold text-slate-900">Per Design Type</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <div v-for="d in byDesignType" :key="d.design_type" class="px-5 py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 bg-red-50 rounded-xl flex items-center justify-center text-xs font-black text-red-700">
                                    {{ d.design_type?.substring(0,2) || '?' }}
                                </span>
                                <span class="text-sm font-semibold text-slate-900">{{ d.design_type || '—' }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-900">{{ fNum(d.total) }} order</p>
                                <p class="text-xs text-slate-400">ODP: {{ fNum(d.odp_real) }} / {{ fNum(d.odp_plan) }}</p>
                            </div>
                        </div>
                        <div v-if="!byDesignType.length" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data</div>
                    </div>
                </div>

                <!-- Top Mitra -->
                <div class="card overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h3 class="text-sm font-bold text-slate-900">Top 10 Mitra</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <div v-for="(m, i) in byMitra" :key="m.mitra_name" class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] font-black text-slate-600">{{ i + 1 }}</span>
                                <span class="text-sm font-medium text-slate-900">{{ m.mitra_name }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-900">{{ fNum(m.total) }}</p>
                                <p class="text-xs text-slate-400">ODP: {{ fNum(m.odp_real) }}</p>
                            </div>
                        </div>
                        <div v-if="!byMitra.length" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
