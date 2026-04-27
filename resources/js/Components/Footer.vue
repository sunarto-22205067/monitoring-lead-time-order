<template>
    <footer class="mt-auto py-6 px-4 lg:px-8 border-t border-slate-200 bg-white">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-[#EE2E24]">TELKOM DMS</span>
                <span class="text-slate-300">|</span>
                <p class="text-[11px] text-slate-500 font-medium">
                    &copy; {{ new Date().getFullYear() }} Dashboard Monitoring System. All rights reserved.
                </p>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-[#EE2E24] transition-colors uppercase tracking-wider">Bantuan</a>
                <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-[#EE2E24] transition-colors uppercase tracking-wider">Panduan Pengguna</a>
                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 rounded-full border border-slate-100">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">System Online</span>
                </div>
            </div>
        </div>
    </footer>
</template>
