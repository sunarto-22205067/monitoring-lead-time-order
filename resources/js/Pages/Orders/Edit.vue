<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ order: Object, mitras: Array, regions: Array, designTypes: Array, statuses: Array });

const form = useForm({
    order_number: props.order.order_number, project_name: props.order.project_name,
    design_type: props.order.design_type || '', status_project: props.order.status_project,
    category: props.order.category || '', program_type: props.order.program_type || '',
    program_year: props.order.program_year || '', regional: props.order.regional || '',
    witel: props.order.witel || '', datel: props.order.datel || '',
    sto: props.order.sto || '', wok: props.order.wok || '',
    mitra_name: props.order.mitra_name || '', surveyor: props.order.surveyor || '',
    household_count: props.order.household_count || 0,
    odp_plan: props.order.odp_plan || 0, odp_realization: props.order.odp_realization || 0,
    port_plan: props.order.port_plan || 0, port_realization: props.order.port_realization || 0,
    capex_per_port: props.order.capex_per_port || 0, po_value: props.order.po_value || 0,
    gr_value: props.order.gr_value || 0, ir_value: props.order.ir_value || 0,
    estimated_go_live: props.order.estimated_go_live || '', actual_go_live: props.order.actual_go_live || '',
    start_date: props.order.start_date || '', finish_installation_date: props.order.finish_installation_date || '',
    duration_automation: props.order.duration_automation || 0, duration_review: props.order.duration_review || 0,
    duration_approval: props.order.duration_approval || 0, duration_go_live: props.order.duration_go_live || 0,
    alpro_data: props.order.alpro_data || '', description: props.order.description || '',
});

function submit() {
    form.put(route('orders.update', props.order.id));
}
</script>

<template>
    <Head title="Edit Order" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('orders.show', order.id)" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Edit Order</h1>
                    <p class="text-sm text-slate-500">{{ order.order_number }}</p>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Informasi Dasar</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Order Number *</label>
                        <input v-model="form.order_number" type="text" class="form-input"/>
                        <p v-if="form.errors.order_number" class="text-xs text-red-500 mt-1">{{ form.errors.order_number }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Project Name *</label>
                        <input v-model="form.project_name" type="text" class="form-input"/>
                        <p v-if="form.errors.project_name" class="text-xs text-red-500 mt-1">{{ form.errors.project_name }}</p>
                    </div>
                    <div>
                        <label class="form-label">Design Type</label>
                        <select v-model="form.design_type" class="form-input">
                            <option value="">Pilih...</option>
                            <option v-for="d in designTypes" :key="d" :value="d">{{ d }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select v-model="form.status_project" class="form-input">
                            <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                        </select>
                    </div>
                    <div><label class="form-label">Program Year</label><input v-model="form.program_year" type="text" class="form-input"/></div>
                    <div><label class="form-label">Program Type</label><input v-model="form.program_type" type="text" class="form-input"/></div>
                    <div><label class="form-label">Kategori</label><input v-model="form.category" type="text" class="form-input"/></div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Regional & Pelaksana</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div><label class="form-label">Regional</label><input v-model="form.regional" type="text" class="form-input"/></div>
                    <div><label class="form-label">Witel</label><input v-model="form.witel" type="text" class="form-input"/></div>
                    <div><label class="form-label">Datel</label><input v-model="form.datel" type="text" class="form-input"/></div>
                    <div><label class="form-label">STO</label><input v-model="form.sto" type="text" class="form-input"/></div>
                    <div><label class="form-label">WOK</label><input v-model="form.wok" type="text" class="form-input"/></div>
                    <div>
                        <label class="form-label">Mitra</label>
                        <input v-model="form.mitra_name" type="text" class="form-input" list="mitra-list"/>
                        <datalist id="mitra-list"><option v-for="m in mitras" :key="m.id" :value="m.name"/></datalist>
                    </div>
                    <div><label class="form-label">Surveyor</label><input v-model="form.surveyor" type="text" class="form-input"/></div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Data Teknis</h3>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    <div><label class="form-label">Rumah Tangga</label><input v-model="form.household_count" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">ODP Rencana</label><input v-model="form.odp_plan" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">ODP Realisasi</label><input v-model="form.odp_realization" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">Port Rencana</label><input v-model="form.port_plan" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">Port Realisasi</label><input v-model="form.port_realization" type="number" min="0" class="form-input"/></div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Keuangan & Timeline</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <div><label class="form-label">Capex/Port</label><input v-model="form.capex_per_port" type="number" step="0.01" min="0" class="form-input"/></div>
                    <div><label class="form-label">PO Value</label><input v-model="form.po_value" type="number" step="0.01" min="0" class="form-input"/></div>
                    <div><label class="form-label">GR Value</label><input v-model="form.gr_value" type="number" step="0.01" min="0" class="form-input"/></div>
                    <div><label class="form-label">IR Value</label><input v-model="form.ir_value" type="number" step="0.01" min="0" class="form-input"/></div>
                    <div><label class="form-label">Start Date</label><input v-model="form.start_date" type="date" class="form-input"/></div>
                    <div><label class="form-label">Est. Go Live</label><input v-model="form.estimated_go_live" type="date" class="form-input"/></div>
                    <div><label class="form-label">Actual Go Live</label><input v-model="form.actual_go_live" type="date" class="form-input"/></div>
                    <div><label class="form-label">Selesai Instalasi</label><input v-model="form.finish_installation_date" type="date" class="form-input"/></div>
                    <div><label class="form-label">Dur. Automation</label><input v-model="form.duration_automation" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">Dur. Review</label><input v-model="form.duration_review" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">Dur. Approval</label><input v-model="form.duration_approval" type="number" min="0" class="form-input"/></div>
                    <div><label class="form-label">Dur. Go Live</label><input v-model="form.duration_go_live" type="number" min="0" class="form-input"/></div>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea v-model="form.description" rows="3" class="form-input"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <Link :href="route('orders.show', order.id)" class="btn-secondary">Batal</Link>
                <button type="submit" :disabled="form.processing" class="btn-primary">
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>Update Order</span>
                </button>
            </div>
        </form>
    </AppLayout>
</template>
