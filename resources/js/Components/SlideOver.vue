<script setup>
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({
    show: Boolean,
    title: String,
    maxWidth: {
        default: 'xl',
        type: String
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});
</script>

<template>
    <teleport to="body">
        <transition
            enter-active-class="ease-in-out duration-500"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in-out duration-500"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="show" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 transition-opacity" @click="close" />
        </transition>

        <div v-show="show" class="fixed inset-0 overflow-hidden z-[60] pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <transition
                        enter-active-class="transform transition ease-in-out duration-500"
                        enter-from-class="translate-x-full"
                        enter-to-class="translate-x-0"
                        leave-active-class="transform transition ease-in-out duration-500"
                        leave-from-class="translate-x-0"
                        leave-to-class="translate-x-full"
                    >
                        <div v-show="show" 
                            class="pointer-events-auto w-screen" 
                            :class="[
                                maxWidth === 'xl' ? 'max-w-xl' : '',
                                maxWidth === '2xl' ? 'max-w-2xl' : '',
                                maxWidth === '3xl' ? 'max-w-3xl' : ''
                            ]"
                        >
                            <div class="flex h-full flex-col overflow-y-auto bg-white shadow-2xl rounded-l-[3rem] border-l border-slate-100">
                                <div class="px-8 py-10 lg:px-12">
                                    <div class="flex items-start justify-between mb-10">
                                        <div class="flex flex-col gap-1">
                                            <h2 class="text-2xl font-black text-slate-900 tracking-tight lowercase">{{ title }}</h2>
                                            <div class="w-8 h-1 bg-rose-600 rounded-full"></div>
                                        </div>
                                        <button @click="close" class="p-3 bg-slate-50 text-slate-400 hover:text-rose-600 rounded-2xl transition-all">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="relative flex-1 px-1">
                                        <slot />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </teleport>
</template>
