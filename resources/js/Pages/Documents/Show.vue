<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    document: Object,
});

const versionForm = useForm({
    file: null,
    comment: '',
});

const submitNewVersion = () => {
    versionForm.post(route('documents.update', props.document.id), {
        onSuccess: () => versionForm.reset(),
    });
};

const download = (versionId = null) => {
    const url = versionId 
        ? route('documents.download', { document: props.document.id, version: versionId })
        : route('documents.download', props.document.id);
    window.location.href = url;
};
</script>

<template>
    <Head title="Detail Dokumen" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 leading-tight">Detail Dokumen</h2>
                    <p class="mt-1 text-sm text-gray-500">Informasi detail dan riwayat versi dokumen.</p>
                </div>
                <div>
                    <Link :href="route('documents.index')" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                        &larr; Kembali ke Daftar
                    </Link>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Document Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Info Card -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ document.title }}
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                {{ document.category?.name || 'Umum' }}
                            </p>
                        </div>
                        <button @click="download()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12" /></svg>
                            Unduh
                        </button>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ document.description || 'Tidak ada deskripsi.' }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pemilik</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ document.user.name }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tim</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ document.team ? document.team.name : 'Pribadi' }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Versi Saat Ini</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    v{{ document.current_version?.version_number }}
                                </dd>
                            </div>
                             <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Ukuran File</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ (document.current_version?.file_size / 1024).toFixed(1) }} KB
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Version History -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                   <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Riwayat Versi</h3>
                    </div>
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="ver in document.versions" :key="ver.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm">
                                            v{{ ver.version_number }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            Uploaded by {{ ver.uploader?.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ ver.created_at }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button @click="download(ver.id)" class="text-gray-400 hover:text-gray-600 transition-colors" title="Download versi ini">
                                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right: Upload New Version -->
            <div class="space-y-6">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Update Versi</h3>
                    </div>
                    <div class="p-6">
                        <form @submit.prevent="submitNewVersion" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Dokumen Baru</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-red-500 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="version-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                <span>Upload a file</span>
                                                <input id="version-upload" name="version-upload" type="file" class="sr-only" @input="versionForm.file = $event.target.files[0]" required>
                                            </label>
                                        </div>
                                         <p v-if="versionForm.file" class="text-xs font-medium text-red-600 mt-2">
                                            {{ versionForm.file.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                             <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700">Catatan Perubahan</label>
                                <textarea id="comment" v-model="versionForm.comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Jelaskan perubahan..."></textarea>
                            </div>
                            <button type="submit" 
                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 transition-colors"
                                :disabled="versionForm.processing || !versionForm.file">
                                Upload Versi Baru
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                     <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm leading-6 font-medium text-gray-900">Meta Info</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 text-sm text-gray-500 space-y-2">
                        <div class="flex justify-between">
                            <span>Dibuat:</span>
                            <span class="text-gray-900">{{ document.created_at }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Diupdate:</span>
                            <span class="text-gray-900">{{ document.updated_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
