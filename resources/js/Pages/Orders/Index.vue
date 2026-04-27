<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    orders: Object,
    filters: Object,
    regionals: Array,
    designTypes: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const regional = ref(props.filters.regional || '');
const designType = ref(props.filters.design_type || '');

const applyFilters = debounce(() => {
    router.get(route('orders.index'), {
        search: search.value,
        status: status.value,
        regional: regional.value,
        design_type: designType.value,
    }, { preserveState: true, replace: true });
}, 400);

watch([search, status, regional, designType], applyFilters);

function statusClass(s) {
    return { active: 'badge-active', completed: 'badge-completed', overdue: 'badge-overdue' }[s] || 'badge-active';
}

function formatNum(v) {
    return Number(v || 0).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Orders" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Data Order</h1>
                    <p class="text-sm text-slate-500">Kelola semua data proyek monitoring</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('orders.upload')" class="btn-secondary text-xs px-3 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        Upload
                    </Link>
                    <Link :href="route('orders.create')" class="btn-primary text-xs px-3 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Order
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Filters -->
            <div class="card p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <input v-model="search" type="text" placeholder="Cari order, proyek, mitra..."
                        class="form-input text-sm px-4 py-2.5"/>
                    <select v-model="status" class="form-input text-sm px-4 py-2.5">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                    <select v-model="regional" class="form-input text-sm px-4 py-2.5">
                        <option value="">Semua Regional</option>
                        <option v-for="r in regionals" :key="r" :value="r">{{ r }}</option>
                    </select>
                    <select v-model="designType" class="form-input text-sm px-4 py-2.5">
                        <option value="">Semua Design Type</option>
                        <option v-for="d in designTypes" :key="d" :value="d">{{ d }}</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header">Order Number</th>
                                <th class="table-header">Project Name</th>
                                <th class="table-header">Regional / Witel</th>
                                <th class="table-header">Mitra</th>
                                <th class="table-header text-center">ODP Real/Plan</th>
                                <th class="table-header text-center">Status</th>
                                <th class="table-header text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-if="orders.data.length === 0">
                                <td colspan="7" class="py-12 text-center text-slate-400 text-sm">Tidak ada data order</td>
                            </tr>
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 transition-colors group">
                                <td class="table-cell">
                                    <span class="font-mono text-xs font-semibold text-red-700 bg-red-50 px-2 py-1 rounded-lg">{{ order.order_number }}</span>
                                </td>
                                <td class="table-cell max-w-[220px]">
                                    <p class="font-semibold text-slate-900 truncate">{{ order.project_name }}</p>
                                    <p class="text-xs text-slate-400">{{ order.design_type || '—' }} · {{ order.program_year || '—' }}</p>
                                </td>
                                <td class="table-cell">
                                    <p class="text-sm font-medium">{{ order.regional || '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ order.witel || '' }}</p>
                                </td>
                                <td class="table-cell text-sm">{{ order.mitra_name || '—' }}</td>
                                <td class="table-cell text-center">
                                    <p class="text-sm font-bold text-slate-900">{{ formatNum(order.odp_realization) }} / {{ formatNum(order.odp_plan) }}</p>
                                    <div class="progress-bar mt-1.5 w-24 mx-auto">
                                        <div class="progress-fill bg-red-500"
                                            :style="`width: ${order.odp_plan > 0 ? Math.min((order.odp_realization/order.odp_plan)*100,100) : 0}%`"></div>
                                    </div>
                                </td>
                                <td class="table-cell text-center">
                                    <span :class="statusClass(order.status_project)">{{ order.status_project }}</span>
                                </td>
                                <td class="table-cell text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Link :href="route('orders.show', order.id)"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </Link>
                                        <Link :href="route('orders.edit', order.id)"
                                            class="p-1.5 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="orders.last_page > 1" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Menampilkan {{ orders.from }}–{{ orders.to }} dari {{ orders.total }} data
                    </p>
                    <div class="flex gap-1">
                        <Link v-for="link in orders.links" :key="link.label"
                            :href="link.url || '#'"
                            :class="['px-3 py-1.5 text-xs font-medium rounded-lg transition-all', link.active ? 'bg-red-600 text-white' : link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 cursor-default']"
                            v-html="link.label"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
