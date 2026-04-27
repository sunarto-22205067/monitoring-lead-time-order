<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const userRoles = computed(() => {
    if (!user.value || !user.value.roles) return [];
    return user.value.roles.map(r => r.name);
});

const welcomeMessage = computed(() => {
    if (userRoles.value.includes('admin')) {
        return 'Selamat bertugas kembali! Berikut adalah kendali penuh sistem monitoring jaringan hari ini.';
    } else if (userRoles.value.includes('mitra')) {
        return 'Pantau progress dan update status pekerjaan Anda di lapangan secara real-time.';
    } else {
        return 'Ringkasan performa monitoring proyek jaringan Telkom.';
    }
});

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    byRegional: { type: Array, default: () => [] },
    byDesignType: { type: Array, default: () => [] },
    byStatus: { type: Array, default: () => [] },
    recentOrders: { type: Array, default: () => [] },
    totalMitras: { type: Number, default: 0 },
    totalUsers: { type: Number, default: 0 },
});

function formatCurrency(val) {
    if (!val) return 'Rp 0';
    return 'Rp ' + Number(val).toLocaleString('id-ID');
}

function formatNum(val) {
    return Number(val || 0).toLocaleString('id-ID');
}

function statusColor(status) {
    const map = { active: 'badge-active', completed: 'badge-completed', overdue: 'badge-overdue' };
    return map[status] || 'badge-active';
}
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">
                        Selamat datang, <span class="text-[#EE2E24]">{{ user?.name || 'Pengguna' }}</span> 👋
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ welcomeMessage }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- KPI Cards Row 1 -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total</span>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ formatNum(stats.totalOrders) }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1">Total Order</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-wide">Aktif</span>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ formatNum(stats.activeOrders) }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1">Proyek Berjalan</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Overdue</span>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ formatNum(stats.overdueOrders) }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1">Perlu Perhatian</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-purple-500 uppercase tracking-wide">Selesai</span>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ formatNum(stats.completedOrders) }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1">Proyek Selesai</p>
                </div>
            </div>

            <!-- Progress Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-bold text-slate-900">Progress ODP</p>
                            <p class="text-xs text-slate-500">Realisasi vs Rencana</p>
                        </div>
                        <span class="text-2xl font-black text-slate-900">{{ stats.odpProgress }}%</span>
                    </div>
                    <div class="progress-bar mb-3">
                        <div class="progress-fill bg-gradient-to-r from-red-500 to-red-600" :style="`width: ${Math.min(stats.odpProgress, 100)}%`"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Realisasi: <strong class="text-slate-900">{{ formatNum(stats.totalOdpRealization) }}</strong></span>
                        <span>Rencana: <strong class="text-slate-900">{{ formatNum(stats.totalOdpPlan) }}</strong></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-bold text-slate-900">Progress Port</p>
                            <p class="text-xs text-slate-500">Realisasi vs Rencana</p>
                        </div>
                        <span class="text-2xl font-black text-slate-900">{{ stats.portProgress }}%</span>
                    </div>
                    <div class="progress-bar mb-3">
                        <div class="progress-fill bg-gradient-to-r from-blue-500 to-blue-600" :style="`width: ${Math.min(stats.portProgress, 100)}%`"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Realisasi: <strong class="text-slate-900">{{ formatNum(stats.totalPortRealization) }}</strong></span>
                        <span>Rencana: <strong class="text-slate-900">{{ formatNum(stats.totalPortPlan) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Financial + Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Financial -->
                <div class="card p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900">Nilai Keuangan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                            <span class="text-xs text-slate-500 font-medium">Total PO Value</span>
                            <span class="text-sm font-bold text-slate-900">{{ formatCurrency(stats.totalPoValue) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                            <span class="text-xs text-slate-500 font-medium">Total GR Value</span>
                            <span class="text-sm font-bold text-green-700">{{ formatCurrency(stats.totalGrValue) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5">
                            <span class="text-xs text-slate-500 font-medium">Total IR Value</span>
                            <span class="text-sm font-bold text-blue-700">{{ formatCurrency(stats.totalIrValue) }}</span>
                        </div>
                    </div>
                    <div class="pt-2 grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-slate-50 rounded-xl">
                            <p class="text-lg font-black text-slate-900">{{ formatNum(totalMitras) }}</p>
                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">Mitra</p>
                        </div>
                        <div class="text-center p-3 bg-slate-50 rounded-xl">
                            <p class="text-lg font-black text-slate-900">{{ formatNum(totalUsers) }}</p>
                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">Users</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-900">Order Terbaru</h3>
                        <a :href="route('orders.index')" class="text-xs text-red-600 hover:text-red-700 font-semibold">Lihat Semua →</a>
                    </div>
                    <div v-if="recentOrders && recentOrders.length" class="space-y-0 divide-y divide-slate-100">
                        <div v-for="order in recentOrders" :key="order.id" class="py-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ order.project_name }}</p>
                                <p class="text-xs text-slate-400">{{ order.order_number }} · {{ order.regional || '—' }}</p>
                            </div>
                            <span :class="statusColor(order.status_project)" class="flex-shrink-0">
                                {{ order.status_project }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-slate-400 text-sm">
                        Belum ada data order
                    </div>
                </div>
            </div>

            <!-- By Regional -->
            <div v-if="byRegional && byRegional.length" class="card overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Distribusi per Regional</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header">Regional</th>
                                <th class="table-header text-right">Total Order</th>
                                <th class="table-header text-right">ODP Rencana</th>
                                <th class="table-header text-right">ODP Realisasi</th>
                                <th class="table-header">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="r in byRegional" :key="r.regional" class="hover:bg-slate-50 transition-colors">
                                <td class="table-cell font-semibold">{{ r.regional || '—' }}</td>
                                <td class="table-cell text-right">{{ formatNum(r.total) }}</td>
                                <td class="table-cell text-right">{{ formatNum(r.odp_plan_total) }}</td>
                                <td class="table-cell text-right">{{ formatNum(r.odp_real) }}</td>
                                <td class="table-cell">
                                    <div class="flex items-center gap-2">
                                        <div class="progress-bar flex-1">
                                            <div class="progress-fill bg-red-500"
                                                :style="`width: ${r.odp_plan_total > 0 ? Math.min((r.odp_real / r.odp_plan_total)*100,100) : 0}%`"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-600 w-10 text-right">
                                            {{ r.odp_plan_total > 0 ? Math.round((r.odp_real / r.odp_plan_total)*100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>