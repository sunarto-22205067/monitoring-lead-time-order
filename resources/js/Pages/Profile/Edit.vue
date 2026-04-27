<script setup>
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = computed(() => usePage().props.auth.user);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileForm = useForm({
    _method: 'PATCH',
    name: user.value.name,
    email: user.value.email,
    photo: null,
});

const updatePasswordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const deleteUserForm = useForm({
    password: '',
});

const updateProfile = () => {
    if (photoInput.value) {
        updateProfileForm.photo = photoInput.value.files[0];
    }
    updateProfileForm.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('profile.photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

const updatePassword = () => {
    updatePasswordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => updatePasswordForm.reset(),
    });
};

const deleteUser = () => {
    if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini permanen.')) {
        deleteUserForm.delete(route('profile.destroy'));
    }
};
</script>

<template>
    <Head title="Pengaturan Profil" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between py-2">
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Pengaturan Profil</h2>
                    <p class="text-slate-500 font-medium text-sm mt-1">Kelola informasi identitas akun dan keamanan akses Anda.</p>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto py-10 space-y-8 pb-20">
            <div class="md:grid md:grid-cols-3 md:gap-8">
                <!-- Left Column: Context -->
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-bold text-slate-900">Identitas & Akses</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Pastikan data profil Anda sudah benar untuk keperluan verifikasi dan keamanan akun sistem.
                        </p>
                    </div>
                </div>

                <!-- Right Column: Settings Forms -->
                <div class="md:col-span-2 space-y-8">
                    
                    <!-- Profile Information Section -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-base font-bold text-slate-800">Informasi Dasar</h3>
                        </div>
                        <div class="p-8">
                            <form @submit.prevent="updateProfile">
                                <!-- Profile Photo -->
                                <div class="mb-8">
                                    <label class="block text-xs font-bold text-slate-700 mb-3 ml-1">Foto Profil</label>
                                    
                                    <div class="flex items-center gap-6">
                                        <!-- Avatar Display -->
                                        <div class="relative group">
                                            <div v-show="!photoPreview" class="h-24 w-24 rounded-2xl overflow-hidden bg-slate-100 border-2 border-slate-200 shadow-sm group-hover:border-red-500 transition-all">
                                                <img :src="user.profile_photo_url" :alt="user.name" class="h-full w-full object-cover" />
                                            </div>

                                            <div v-show="photoPreview" class="h-24 w-24 rounded-2xl overflow-hidden bg-slate-100 border-2 border-red-500 shadow-sm">
                                                <span class="block h-full w-full bg-cover bg-no-repeat bg-center"
                                                      :style="'background-image: url(\'' + photoPreview + '\');'">
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2">
                                            <input type="file" ref="photoInput" class="hidden" @change="updatePhotoPreview" />

                                            <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 uppercase tracking-wider hover:bg-slate-50 transition-all" @click.prevent="selectNewPhoto">
                                                Unggah Foto Baru
                                            </button>

                                            <button v-if="user.profile_photo_path" type="button" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 border border-red-100 rounded-lg text-xs font-bold text-red-600 uppercase tracking-wider hover:bg-red-100 transition-all" @click.prevent="deletePhoto">
                                                Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="updateProfileForm.errors.photo" class="text-red-500 text-xs mt-3 font-semibold">{{ updateProfileForm.errors.photo }}</div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div class="space-y-1.5">
                                        <label for="name" class="text-xs font-bold text-slate-700 ml-1">Nama Lengkap</label>
                                        <input id="name" v-model="updateProfileForm.name" type="text" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" autocomplete="name" />
                                        <div v-if="updateProfileForm.errors.name" class="text-red-500 text-xs mt-1">{{ updateProfileForm.errors.name }}</div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="email" class="text-xs font-bold text-slate-700 ml-1">Alamat Email</label>
                                        <input id="email" v-model="updateProfileForm.email" type="email" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" autocomplete="email" />
                                        <div v-if="updateProfileForm.errors.email" class="text-red-500 text-xs mt-1">{{ updateProfileForm.errors.email }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                                    <transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                        <span v-if="updateProfileForm.recentlySuccessful" class="text-xs font-bold text-emerald-600">Berhasil diperbarui.</span>
                                    </transition>
                                    <button type="submit" :disabled="updateProfileForm.processing" class="inline-flex justify-center py-2.5 px-6 bg-slate-900 text-white text-xs font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-all disabled:opacity-50 uppercase tracking-wider">
                                        Simpan Profil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Update Password Section -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-base font-bold text-slate-800">Keamanan & Password</h3>
                        </div>
                        <div class="p-8">
                            <form @submit.prevent="updatePassword">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="space-y-1.5">
                                        <label for="current_password" class="text-xs font-bold text-slate-700 ml-1">Password Saat Ini</label>
                                        <input id="current_password" v-model="updatePasswordForm.current_password" type="password" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" autocomplete="current-password" />
                                        <div v-if="updatePasswordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ updatePasswordForm.errors.current_password }}</div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5">
                                            <label for="password" class="text-xs font-bold text-slate-700 ml-1">Password Baru</label>
                                            <input id="password" v-model="updatePasswordForm.password" type="password" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" autocomplete="new-password" />
                                            <div v-if="updatePasswordForm.errors.password" class="text-red-500 text-xs mt-1">{{ updatePasswordForm.errors.password }}</div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="password_confirmation" class="text-xs font-bold text-slate-700 ml-1">Konfirmasi Password Baru</label>
                                            <input id="password_confirmation" v-model="updatePasswordForm.password_confirmation" type="password" class="block w-full bg-white border-slate-200 rounded-xl h-12 px-4 text-sm font-medium text-slate-900 focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" autocomplete="new-password" />
                                            <div v-if="updatePasswordForm.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ updatePasswordForm.errors.password_confirmation }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                                    <transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                        <span v-if="updatePasswordForm.recentlySuccessful" class="text-xs font-bold text-emerald-600">Password berhasil diubah.</span>
                                    </transition>
                                    <button type="submit" :disabled="updatePasswordForm.processing" class="inline-flex justify-center py-2.5 px-6 bg-red-600 text-white text-xs font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all disabled:opacity-50 uppercase tracking-wider">
                                        Perbarui Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account Section -->
                    <div class="bg-red-50/50 rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-red-700">Area Sangat Penting: Hapus Akun</h3>
                                    <p class="text-xs text-red-600 opacity-80 mt-0.5">Penghapusan akun akan menghilangkan seluruh data secara permanen.</p>
                                </div>
                            </div>
                            <div class="flex justify-start">
                                <button type="button" @click="deleteUser" class="inline-flex justify-center py-2 px-5 bg-red-600 text-white text-[10px] font-black rounded-lg hover:bg-red-700 transition-all uppercase tracking-widest">
                                    Hapus Akun Selamanya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
