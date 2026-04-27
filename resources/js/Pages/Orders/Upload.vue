<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({ file: null });
const fileName = ref('');
const dragOver = ref(false);

function onFileChange(e) {
    const file = e.target.files[0];
    if (file) { form.file = file; fileName.value = file.name; }
}
function onDrop(e) {
    dragOver.value = false;
    const file = e.dataTransfer?.files[0];
    if (file) { form.file = file; fileName.value = file.name; }
}
function submit() {
    form.post(route('orders.process-upload'), {
        forceFormData: true,
        onSuccess: () => { form.reset(); fileName.value = ''; },
    });
}
</script>

<template>
    <Head title="Upload Data" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('orders.index')" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <h1 class="text-lg font-bold text-slate-900">Upload Data Order</h1>
            </div>
        </template>
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="card p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Panduan</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li>✅ Format: <strong>.xlsx, .xls, .csv</strong></li>
                    <li>✅ Maksimal <strong>10 MB</strong></li>
                    <li>ℹ️ Pastikan header kolom sesuai template</li>
                </ul>
            </div>
            <div class="card p-8">
                <form @submit.prevent="submit">
                    <label @dragover.prevent="dragOver = true" @dragleave="dragOver = false" @drop.prevent="onDrop"
                        :class="['block border-2 border-dashed rounded-2xl p-10 text-center cursor-pointer transition-all',
                            dragOver ? 'border-red-400 bg-red-50' : 'border-slate-200 hover:border-red-300 hover:bg-slate-50']">
                        <input type="file" class="sr-only" accept=".xlsx,.xls,.csv" @change="onFileChange"/>
                        <div class="flex flex-col items-center gap-3">
                            <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center', fileName ? 'bg-green-100' : 'bg-slate-100']">
                                <svg v-if="!fileName" class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <svg v-else class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p v-if="fileName" class="text-sm font-bold text-green-700">{{ fileName }}</p>
                            <p v-else class="text-sm font-semibold text-slate-600">Klik atau seret file di sini</p>
                        </div>
                    </label>
                    <p v-if="form.errors.file" class="text-xs text-red-500 mt-2">{{ form.errors.file }}</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <button v-if="fileName" type="button" @click="form.file = null; fileName = ''" class="btn-secondary">Ganti File</button>
                        <button type="submit" :disabled="!form.file || form.processing" class="btn-primary">
                            <span v-if="form.processing">Mengupload...</span>
                            <span v-else>Upload Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
