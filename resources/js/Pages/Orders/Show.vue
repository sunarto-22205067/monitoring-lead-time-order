<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ order: Object });

function fNum(v) { return Number(v || 0).toLocaleString('id-ID'); }
function fCur(v) { return 'Rp ' + Number(v || 0).toLocaleString('id-ID'); }
function fDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
}
function statusClass(s) {
    return { active: 'badge-active', completed: 'badge-completed', overdue: 'badge-overdue' }[s] || 'badge-active';
}
function pct(real, plan) {
    if (!plan || plan <= 0) return 0;
    return Math.min(Math.round((real / plan) * 100), 100);
}

const deleteForm = useForm({});
function deleteOrder() {
    if (confirm('Hapus order ini?')) {
        deleteForm.delete(route('orders.destroy', props.order.id));
    }
}
</script>

<template>
    <Head :title="order.project_name" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <Link :href="route('orders.index')" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </Link>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900">{{ order.project_name }}</h1>
                        <p class="text-sm text-slate-500">{{ order.order_number }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('orders.edit', order.id)" class="btn-secondary text-xs px-3 py-2">Edit</Link>
                    <button @click="deleteOrder" class="btn-danger text-xs px-3 py-2">Hapus</button>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <!-- Status -->
                <div class="card p-5 flex flex-wrap items-center gap-4">
                    <span :class="statusClass(order.status_project)">{{ order.status_project?.toUpperCase() }}</span>
                    <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono">{{ order.design_type || '—' }}</span>
                    <span class="text-xs text-slate-500">Program: <strong class="text-slate-800">{{ order.program_year || '—' }}</strong></span>
                </div>

                <!-- Progress -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="card p-5">
                        <div class="flex justify-between mb-3">
                            <p class="text-sm font-bold text-slate-900">ODP</p>
                            <span class="text-xl font-black">{{ pct(order.odp_realization, order.odp_plan) }}%</span>
                        </div>
                        <div class="progress-bar mb-3">
                            <div class="progress-fill bg-red-500" :style="`width:${pct(order.odp_realization, order.odp_plan)}%`"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="bg-slate-50 rounded-xl p-3">
                                <p class="text-base font-black text-slate-900">{{ fNum(order.odp_plan) }}</p>
                                <p class="text-[10px] text-slate-400 uppercase">Rencana</p>
                            </div>
                            <div class="bg-red-50 rounded-xl p-3">
                                <p class="text-base font-black text-red-700">{{ fNum(order.odp_realization) }}</p>
                                <p class="text-[10px] text-red-400 uppercase">Realisasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="card p-5">
                        <div class="flex justify-between mb-3">
                            <p class="text-sm font-bold text-slate-900">Port</p>
                            <span class="text-xl font-black">{{ pct(order.port_realization, order.port_plan) }}%</span>
                        </div>
                        <div class="progress-bar mb-3">
                            <div class="progress-fill bg-blue-500" :style="`width:${pct(order.port_realization, order.port_plan)}%`"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="bg-slate-50 rounded-xl p-3">
                                <p class="text-base font-black text-slate-900">{{ fNum(order.port_plan) }}</p>
                                <p class="text-[10px] text-slate-400 uppercase">Rencana</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3">
                                <p class="text-base font-black text-blue-700">{{ fNum(order.port_realization) }}</p>
                                <p class="text-[10px] text-blue-400 uppercase">Realisasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Grid -->
                <div class="card overflow-hidden">
                    <div class="p-5 bg-slate-50 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900">Informasi Proyek</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                        <dl class="divide-y divide-slate-100">
                            <div v-for="[label, val] in [['Regional', order.regional], ['Witel', order.witel], ['Datel', order.datel], ['STO', order.sto], ['WOK', order.wok], ['Rumah Tangga', fNum(order.household_count)]]"
                                :key="label" class="px-5 py-3 flex justify-between gap-4">
                                <dt class="text-xs text-slate-500">{{ label }}</dt>
                                <dd class="text-xs font-semibold text-slate-900 text-right">{{ val || '—' }}</dd>
                            </div>
                        </dl>
                        <dl class="divide-y divide-slate-100">
                            <div v-for="[label, val] in [['Mitra', order.mitra_name], ['Surveyor', order.surveyor], ['Program Type', order.program_type], ['Kategori', order.category], ['Capex/Port', fCur(order.capex_per_port)], ['PO Value', fCur(order.po_value)]]"
                                :key="label" class="px-5 py-3 flex justify-between gap-4">
                                <dt class="text-xs text-slate-500">{{ label }}</dt>
                                <dd class="text-xs font-semibold text-slate-900 text-right">{{ val || '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="space-y-5">
                <div class="card overflow-hidden">
                    <div class="p-5 bg-slate-50 border-b border-slate-100"><h3 class="text-sm font-bold text-slate-900">Timeline</h3></div>
                    <dl class="divide-y divide-slate-100">
                        <div v-for="[label, val] in [['Start Date', fDate(order.start_date)], ['Est. Go Live', fDate(order.estimated_go_live)], ['Actual Go Live', fDate(order.actual_go_live)], ['Selesai Instalasi', fDate(order.finish_installation_date)]]"
                            :key="label" class="px-5 py-3.5">
                            <dt class="text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">{{ label }}</dt>
                            <dd class="text-sm font-semibold text-slate-900">{{ val }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="card overflow-hidden">
                    <div class="p-5 bg-slate-50 border-b border-slate-100"><h3 class="text-sm font-bold text-slate-900">Durasi (Hari)</h3></div>
                    <dl class="divide-y divide-slate-100">
                        <div v-for="[label, val] in [['Automation', order.duration_automation], ['Review', order.duration_review], ['Approval', order.duration_approval], ['Go Live', order.duration_go_live]]"
                            :key="label" class="px-5 py-3 flex justify-between">
                            <dt class="text-xs text-slate-500">{{ label }}</dt>
                            <dd class="text-xs font-bold text-slate-900">{{ val || 0 }} hari</dd>
                        </div>
                    </dl>
                </div>
                <div class="card overflow-hidden">
                    <div class="p-5 bg-slate-50 border-b border-slate-100"><h3 class="text-sm font-bold text-slate-900">Nilai Keuangan</h3></div>
                    <dl class="divide-y divide-slate-100">
                        <div class="px-5 py-3 flex justify-between"><dt class="text-xs text-slate-500">GR Value</dt><dd class="text-xs font-bold text-green-700">{{ fCur(order.gr_value) }}</dd></div>
                        <div class="px-5 py-3 flex justify-between"><dt class="text-xs text-slate-500">IR Value</dt><dd class="text-xs font-bold text-blue-700">{{ fCur(order.ir_value) }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
