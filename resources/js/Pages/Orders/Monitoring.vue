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
    router.get(route('orders.monitoring'), {
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

function progressPct(real, plan) {
    if (!plan || plan <= 0) return 0;
    return Math.min(Math.round((real / plan) * 100), 100);
}

function formatNum(v) {
    return Number(v || 0).toLocaleString('id-ID');
}

function progressColor(pct) {
    if (pct >= 80) return 'bg-green-500';
    if (pct >= 50) return 'bg-yellow-500';
    return 'bg-red-500';
}
</script>

<template>
    <Head title="Monitoring" />
    <AppLayout>
        <template #header>
            <div>
                <h1 class="text-lg font-bold text-slate-900">Monitoring Proyek</h1>
                <p class="text-sm text-slate-500">Pantau progress ODP dan port realisasi secara real-time</p>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Filters -->
            <div class="card p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <input v-model="search" type="text" placeholder="Cari proyek atau order..."
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

            <!-- Monitoring Cards -->
            <div v-if="orders.data.length === 0" class="card p-12 text-center text-slate-400">
                Tidak ada data monitoring
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div v-for="order in orders.data" :key="order.id"
                    class="card p-5 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="min-w-0 flex-1">
                            <span class="font-mono text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-lg">{{ order.order_number }}</span>
                            <h3 class="text-sm font-bold text-slate-900 mt-1.5 leading-tight">{{ order.project_name }}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ order.regional }} · {{ order.witel || '' }} · {{ order.design_type || '' }}</p>
                        </div>
                        <span :class="statusClass(order.status_project)" class="ml-3 flex-shrink-0">{{ order.status_project }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <!-- ODP -->
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">ODP</span>
                                <span class="text-xs font-black text-slate-900">{{ progressPct(order.odp_realization, order.odp_plan) }}%</span>
                            </div>
                            <div class="progress-bar mb-1.5">
                                <div class="progress-fill" :class="progressColor(progressPct(order.odp_realization, order.odp_plan))"
                                    :style="`width:${progressPct(order.odp_realization, order.odp_plan)}%`"></div>
                            </div>
                            <p class="text-[10px] text-slate-400">{{ formatNum(order.odp_realization) }} / {{ formatNum(order.odp_plan) }}</p>
                        </div>
                        <!-- Port -->
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">Port</span>
                                <span class="text-xs font-black text-slate-900">{{ progressPct(order.port_realization, order.port_plan) }}%</span>
                            </div>
                            <div class="progress-bar mb-1.5">
                                <div class="progress-fill" :class="progressColor(progressPct(order.port_realization, order.port_plan))"
                                    :style="`width:${progressPct(order.port_realization, order.port_plan)}%`"></div>
                            </div>
                            <p class="text-[10px] text-slate-400">{{ formatNum(order.port_realization) }} / {{ formatNum(order.port_plan) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div class="text-xs text-slate-400">
                            Mitra: <span class="font-semibold text-slate-600">{{ order.mitra_name || '—' }}</span>
                        </div>
                        <Link :href="route('orders.show', order.id)"
                            class="text-xs font-semibold text-red-600 hover:text-red-700 transition-colors">
                            Detail →
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="flex justify-center gap-1">
                <Link v-for="link in orders.links" :key="link.label"
                    :href="link.url || '#'"
                    :class="['px-3 py-1.5 text-xs font-medium rounded-lg transition-all', link.active ? 'bg-red-600 text-white' : link.url ? 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' : 'text-slate-300 cursor-default']"
                    v-html="link.label"/>
            </div>
        </div>
    </AppLayout>
</template>
