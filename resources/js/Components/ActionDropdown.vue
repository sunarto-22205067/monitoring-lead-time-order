<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const show = ref(false);
const trigger = ref(null);
const dropdown = ref(null);

const toggle = () => show.value = !show.value;
const close = () => show.value = false;

const closeOnClickOutside = (e) => {
    if (show.value && !trigger.value?.contains(e.target) && !dropdown.value?.contains(e.target)) {
        close();
    }
};

onMounted(() => document.addEventListener('mousedown', closeOnClickOutside));
onUnmounted(() => document.removeEventListener('mousedown', closeOnClickOutside));
</script>

<template>
    <div class="relative inline-block text-left">
        <button ref="trigger" @click="toggle" class="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-slate-900 hover:border-slate-300 hover:shadow-md transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div v-show="show" ref="dropdown" class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-2xl bg-white p-2 shadow-2xl ring-1 ring-slate-100 focus:outline-none">
                <div class="flex flex-col gap-1" @click="close">
                    <slot />
                </div>
            </div>
        </transition>
    </div>
</template>
