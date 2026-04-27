<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome - SIMDOK LTO" />

    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('welcome')" class="flex items-center">
                            <div class="flex-shrink-0 bg-red-600 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="ml-3 text-xl font-bold text-gray-900">SIMDOK <span class="text-red-600">LTO</span></span>
                        </Link>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-sm font-medium text-gray-700 hover:text-red-600 transition">Fitur</a>
                        <a href="#about" class="text-sm font-medium text-gray-700 hover:text-red-600 transition">Tentang</a>
                        
                        <div v-if="$page.props.auth.user" class="flex items-center space-x-4">
                            <Link :href="route('dashboard')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 transition">
                                Dashboard
                            </Link>
                        </div>
                        <div v-else class="flex items-center space-x-4">
                            <Link :href="route('login')" class="text-sm font-medium text-gray-700 hover:text-red-600 transition">
                                Masuk
                            </Link>
                            <Link :href="route('register')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                Daftar
                            </Link>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button class="text-gray-700 hover:text-red-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800 mb-4">
                                Transformasi Digital Perusahaan
                            </div>
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Kelola Dokumen</span>
                                <span class="block text-red-600 xl:inline">Lebih Cepat & Baik</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Generasi terbaru sistem manajemen dokumen perusahaan. Simpan, bagikan, dan lacak semua dokumen tim Anda dengan aman dalam satu platform terpadu.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <Link :href="route('register')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg md:px-10 transition">
                                        Mulai Sekarang
                                    </Link>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 md:py-4 md:text-lg md:px-10 transition">
                                        Jelajahi Fitur
                                    </a>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-center lg:justify-start space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Gratis untuk memulai</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Tanpa kartu kredit</span>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" alt="Team collaboration">
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-red-600 font-semibold tracking-wide uppercase">Fitur Standar Perusahaan</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Dirancang untuk skala besar dan dibangun untuk tenaga kerja modern
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Dengan keandalan Telkom Indonesia
                    </p>
                </div>

                <div class="mt-10">
                    <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                        <div class="relative bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="absolute -top-4 left-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white shadow-lg">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-5 text-lg leading-6 font-medium text-gray-900">Sangat Aman</p>
                            <p class="mt-2 text-base text-gray-500">
                                Enkripsi ujung-ke-ujung dan kontrol akses granular untuk setiap dokumen.
                            </p>
                        </div>

                        <div class="relative bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="absolute -top-4 left-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white shadow-lg">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-5 text-lg leading-6 font-medium text-gray-900">Riwayat Versi</p>
                            <p class="mt-2 text-base text-gray-500">
                                Jangan pernah kehilangan progres. Lacak setiap perubahan dan pulihkan versi apa pun.
                            </p>
                        </div>

                        <div class="relative bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="absolute -top-4 left-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white shadow-lg">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-5 text-lg leading-6 font-medium text-gray-900">Fokus pada Tim</p>
                            <p class="mt-2 text-base text-gray-500">
                                Atur dokumen berdasarkan tim dan divisi untuk kolaborasi yang lancar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-red-600">
            <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Siap memodernisasi alur kerja Anda?</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-red-100">
                    Bergabunglah dengan ratusan tim yang telah menggunakan SIMDOK LTO untuk merampingkan operasional mereka.
                </p>
                <Link :href="route('register')" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-red-600 bg-white hover:bg-red-50 sm:w-auto transition">
                    Buat Akun Gratis
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>
