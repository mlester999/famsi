<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700"
    >
        <slot name="icon"></slot>

        <slot name="title"></slot>
    </Link>
</template>
