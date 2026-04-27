<script setup>
import { onMounted, watch } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    settings: {
        app_name: props.settings.app_name || 'Telkom DMS',
        app_version: props.settings.app_version || '2.0.0',
        company_address: props.settings.company_address || '',
        company_phone: props.settings.company_phone || '',
        company_email: props.settings.company_email || '',
        developer_name: props.settings.developer_name || '',
    },
    theme: 'light', // Tema bersifat lokal
});

const darkMode = usePage().props.settings?.theme === 'dark' || false;

const toggleDarkMode = () => {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
};

const saveSettings = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Notifikasi sukses ditangani oleh flash message
        },
    });
};

onMounted(() => {
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <Head title="Konfigurasi Sistem Utama" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between py-2">
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Pengaturan Global</h2>
                    <p class="text-slate-500 font-medium text-sm mt-1">Konfigurasi parameter sistem dan identitas infrastruktur utama.</p>
                </div>
                <button @click="saveSettings" :disabled="form.processing" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg hover:bg-slate-800 transition-all disabled:opacity-50">
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
            </div>
        </template>

        <div class="max-w-4xl mx-auto space-y-8 mt-8 pb-20">
            <!-- Pesan Berhasil -->
            <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="$page.props.flash.success" class="fixed top-24 right-8 z-50 bg-white border border-slate-200 shadow-xl rounded-xl p-4 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Berhasil</h4>
                        <p class="text-xs text-slate-500">{{ $page.props.flash.success }}</p>
                    </div>
                </div>
            </transition>

            <!-- Seksi Informasi Aplikasi -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Identitas Aplikasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Informasi dasar yang akan ditampilkan di seluruh sistem.</p>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 ml-1">Nama Sistem</label>
                        <input v-model="form.settings.app_name" type="text" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 ml-1">Label Versi</label>
                        <input v-model="form.settings.app_version" type="text" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-slate-700 ml-1">Pengembang Sistem</label>
                        <input v-model="form.settings.developer_name" type="text" placeholder="Nama Instansi / Developer" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Seksi Kontak -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Korespondensi Korporat</h3>
                    <p class="text-xs text-slate-500 mt-1">Detail kontak perusahaan yang muncul pada bagian footer dokumen.</p>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 ml-1">Alamat Kantor Pusat</label>
                        <textarea v-model="form.settings.company_address" rows="3" class="block w-full bg-white border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 ml-1">Saluran Telepon Resmi</label>
                            <input v-model="form.settings.company_phone" type="text" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 ml-1">Email Komunikasi Resmi</label>
                            <input v-model="form.settings.company_email" type="email" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tampilan -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Preferensi Antarmuka</h3>
                    <p class="text-xs text-slate-500 mt-1">Atur kenyamanan visual penggunaan aplikasi.</p>
                </div>
                <div class="p-8">
                    <div @click="toggleDarkMode" class="flex items-center justify-between p-6 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-300 transition-all cursor-pointer group select-none">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 group-hover:bg-slate-900 group-hover:text-white transition-all transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Mode Gelap (Dark Mode)</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Penyesuaian cahaya untuk kenyamanan mata di malam hari.</p>
                            </div>
                        </div>
                        <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-slate-200 dark:bg-red-600">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 dark:translate-x-5"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi Bawah -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4">
                <button @click="saveSettings" :disabled="form.processing" class="inline-flex items-center justify-center px-10 py-3 bg-red-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all disabled:opacity-50">
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ form.processing ? 'Menyinkronkan...' : 'Simpan Seluruh Pengaturan' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
