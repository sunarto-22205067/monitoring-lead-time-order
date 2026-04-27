<script setup>
const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    type: {
        default: 'danger',
        type: String
    }
});

const emit = defineEmits(['close', 'confirm']);

const close = () => emit('close');
const confirm = () => emit('confirm');
</script>

<template>
    <teleport to="body">
        <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="show" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[70] transition-opacity" @click="close" />
        </transition>

        <div v-show="show" class="fixed inset-0 z-[80] overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div v-show="show" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="p-10">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-20 h-20 rounded-[2rem] flex items-center justify-center mb-6"
                                    :class="type === 'danger' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'"
                                >
                                    <svg v-if="type === 'danger'" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <svg v-else class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                                
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight lowercase">{{ title }}</h3>
                                <p class="mt-4 text-[10px] font-bold text-slate-400 leading-relaxed uppercase tracking-[0.2em] italic">{{ message }}</p>
                            </div>

                            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                                <button @click="confirm" 
                                    class="w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all"
                                    :class="type === 'danger' ? 'bg-rose-600 text-white shadow-xl shadow-rose-200 hover:bg-rose-700' : 'bg-emerald-600 text-white shadow-xl shadow-emerald-200 hover:bg-emerald-700'"
                                >
                                    Ya, Lanjutkan
                                </button>
                                <button @click="close" class="w-full py-4 bg-slate-50 text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-100 transition-all">
                                    Batalkan
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </teleport>
</template>
