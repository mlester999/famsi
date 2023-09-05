<script setup>
defineProps({
    name: String,
});
</script>

<template>
    <div class="flex flex-col">
        <h1
            class="px-4 pt-3 pb-2 font-black uppercase text-gray-500 dark:text-gray-400 text-xs sm:text-sm"
        >
            {{ name }}
        </h1>
        <div
            class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
        >
            <ul class="pb-2 space-y-2">
                <slot name="tab"> </slot>
            </ul>
        </div>
    </div>
</template>
