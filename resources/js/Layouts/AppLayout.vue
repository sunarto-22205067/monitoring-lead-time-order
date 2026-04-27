<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

const sidebarOpen = ref(false);
const userMenuOpen = ref(false);

const navigation = [
    {
        label: 'Overview',
        items: [
            { name: 'Dashboard', href: route('dashboard'), icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', routeName: 'dashboard' },
        ]
    },
    {
        label: 'Monitoring',
        items: [
            { name: 'Monitoring', href: route('orders.monitoring'), icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', routeName: 'orders.monitoring' },
            { name: 'Analytics', href: route('orders.analytics'), icon: 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', routeName: 'orders.analytics' },
            { name: 'Orders', href: route('orders.index'), icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', routeName: 'orders.index' },
            { name: 'Upload Data', href: route('orders.upload'), icon: 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12', routeName: 'orders.upload' },
        ]
    },
    {
        label: 'Master Data',
        items: [
            { name: 'Mitra', href: route('mitras.index'), icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', routeName: 'mitras.index' },
            { name: 'Region', href: route('regions.index'), icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', routeName: 'regions.index' },
        ]
    },
    {
        label: 'Administrasi',
        items: [
            { name: 'Users', href: route('users.index'), icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', routeName: 'users.index' },
            { name: 'Pengaturan', href: route('settings.index'), icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', routeName: 'settings.index' },
        ]
    },
];

function isActive(routeName) {
    return page.component.startsWith(routeName.replace('.', '/').replace(/\./g, '/'));
}

function logout() {
    router.post(route('logout'));
}

const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Mobile Overlay -->
        <transition name="fade">
            <div v-if="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"></div>
        </transition>

        <!-- Sidebar -->
        <aside :class="['fixed inset-y-0 left-0 z-30 w-72 flex flex-col bg-[#EE2E24] shadow-2xl lg:static lg:shadow-none transition-all duration-300 ease-in-out', sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">
            <!-- Logo Area -->
            <div class="flex items-center justify-between px-6 py-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner flex-shrink-0 border border-white/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-base font-black text-white tracking-tight uppercase">Telkom DMS</p>
                        <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest">Monitoring</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-8">
                <div v-for="group in navigation" :key="group.label">
                    <p class="px-4 mb-2 text-[10px] font-extrabold text-white/50 uppercase tracking-[0.2em]">{{ group.label }}</p>
                    <ul class="space-y-0.5">
                        <li v-for="item in group.items" :key="item.name">
                            <Link :href="item.href"
                                :class="page.url.startsWith('/' + item.routeName.split('.')[0])
                                    ? 'sidebar-link-active' : 'sidebar-link'"
                                @click="sidebarOpen = false">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon"/>
                                </svg>
                                <span>{{ item.name }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- User Area -->
            <div class="p-3 border-t border-white/10">
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition-all text-left">
                        <img :src="user.profile_photo_url" :alt="user.name" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0"/>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ user.name }}</p>
                            <p class="text-[10px] text-white/60 truncate">{{ user.email }}</p>
                        </div>
                        <svg class="w-4 h-4 text-white/40 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <transition name="fade">
                        <div v-if="userMenuOpen" class="absolute bottom-full left-0 right-0 mb-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50">
                            <Link :href="route('profile.edit')" class="flex items-center gap-2.5 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </Link>
                            <button @click="logout" class="w-full flex items-center gap-2.5 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors border-t border-slate-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </div>
                    </transition>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-slate-100 px-4 lg:px-8 py-5 flex items-center gap-5 sticky top-0 z-10">
                <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-[#EE2E24] transition-all border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="flex-1">
                    <slot name="header">
                        <h1 class="text-lg font-bold text-slate-900">Dashboard</h1>
                    </slot>
                </div>

                <!-- Notifications placeholder -->
                <div class="flex items-center gap-2">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-bold">
                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                        TELKOM DMS
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="flash.success || flash.error" class="px-4 lg:px-6 pt-4">
                <div v-if="flash.success"
                    class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ flash.success }}</span>
                </div>
                <div v-if="flash.error"
                    class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ flash.error }}</span>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
