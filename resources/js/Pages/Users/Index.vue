<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import debounce from 'lodash/debounce';

const props = defineProps({ users: Object, filters: Object, roles: Array });

const search = ref(props.filters.search || '');
const showForm = ref(false);
const editingUser = ref(null);

const applySearch = debounce(() => {
    router.get(route('users.index'), { search: search.value }, { preserveState: true, replace: true });
}, 400);
watch(search, applySearch);

const form = useForm({ name: '', email: '', password: '', password_confirmation: '', role: '' });

function openCreate() { editingUser.value = null; form.reset(); showForm.value = true; }
function openEdit(user) {
    editingUser.value = user;
    form.name = user.name; form.email = user.email;
    form.role = user.roles?.[0]?.name || '';
    form.password = ''; form.password_confirmation = '';
    showForm.value = true;
}
function closeForm() { showForm.value = false; form.reset(); }
function submit() {
    if (editingUser.value) {
        form.put(route('users.update', editingUser.value.id), { onSuccess: closeForm });
    } else {
        form.post(route('users.store'), { onSuccess: closeForm });
    }
}
function deleteUser(user) {
    if (confirm(`Hapus user "${user.name}"?`)) router.delete(route('users.destroy', user.id));
}
</script>

<template>
    <Head title="Users" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Manajemen User</h1>
                    <p class="text-sm text-slate-500">Kelola akun pengguna sistem</p>
                </div>
                <button @click="openCreate" class="btn-primary text-xs px-3 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah User
                </button>
            </div>
        </template>

        <div class="space-y-4">
            <div class="card p-4">
                <input v-model="search" type="text" placeholder="Cari nama atau email..." class="form-input max-w-sm text-sm px-4 py-2.5"/>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header">User</th>
                            <th class="table-header">Email</th>
                            <th class="table-header">Role</th>
                            <th class="table-header">Bergabung</th>
                            <th class="table-header text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-if="!users.data.length">
                            <td colspan="5" class="py-12 text-center text-slate-400 text-sm">Tidak ada data user</td>
                        </tr>
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50">
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <img :src="user.profile_photo_url || `https://ui-avatars.com/api/?name=${user.name}&background=EE2E24&color=fff`"
                                        :alt="user.name" class="w-8 h-8 rounded-full object-cover"/>
                                    <span class="font-semibold text-slate-900">{{ user.name }}</span>
                                </div>
                            </td>
                            <td class="table-cell text-slate-500">{{ user.email }}</td>
                            <td class="table-cell">
                                <span v-if="user.roles?.length" class="text-xs font-semibold px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg">
                                    {{ user.roles[0].name }}
                                </span>
                                <span v-else class="text-xs text-slate-400">—</span>
                            </td>
                            <td class="table-cell text-slate-500 text-xs">
                                {{ new Date(user.created_at).toLocaleDateString('id-ID') }}
                            </td>
                            <td class="table-cell text-right">
                                <div class="flex justify-end gap-1">
                                    <button @click="openEdit(user)" class="p-1.5 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteUser(user)" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="users.last_page > 1" class="p-4 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-xs text-slate-500">{{ users.from }}–{{ users.to }} dari {{ users.total }}</p>
                    <div class="flex gap-1">
                        <a v-for="link in users.links" :key="link.label" :href="link.url || '#'"
                            :class="['px-3 py-1.5 text-xs font-medium rounded-lg', link.active ? 'bg-red-600 text-white' : link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 cursor-default']"
                            v-html="link.label"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <transition name="fade">
            <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-base font-bold text-slate-900">{{ editingUser ? 'Edit User' : 'Tambah User' }}</h3>
                        <button @click="closeForm" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form @submit.prevent="submit" class="p-6 space-y-4">
                        <div>
                            <label class="form-label">Nama *</label>
                            <input v-model="form.name" type="text" class="form-input" required/>
                            <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="form-label">Email *</label>
                            <input v-model="form.email" type="email" class="form-input" required/>
                            <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
                        </div>
                        <div v-if="!editingUser">
                            <label class="form-label">Password *</label>
                            <input v-model="form.password" type="password" class="form-input" required/>
                            <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p>
                        </div>
                        <div v-if="!editingUser">
                            <label class="form-label">Konfirmasi Password *</label>
                            <input v-model="form.password_confirmation" type="password" class="form-input" required/>
                        </div>
                        <div>
                            <label class="form-label">Role</label>
                            <select v-model="form.role" class="form-input">
                                <option value="">Tidak ada role</option>
                                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="closeForm" class="btn-secondary">Batal</button>
                            <button type="submit" :disabled="form.processing" class="btn-primary">
                                {{ editingUser ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>
