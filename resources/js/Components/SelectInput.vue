<script setup>
defineProps({
    id: String,
    labelValue: String,
});
</script>

<template>
    <label
        :for="id"
        class="block text-sm font-medium text-gray-900 dark:text-white"
        >{{ labelValue }}</label
    >
    <select
        :id="id"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
    >
        <slot />
    </select>
</template>
