<script setup>
import TableCrud from "@/Components/TableCrud.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Files">
        <TableCrud name="files">
            <template #first-tab>
                <p
                    class="inline-flex items-center text-gray-700 dark:text-gray-300"
                >
                    <font-awesome-icon
                        class="w-5 h-5 mr-2.5"
                        :icon="['fas', 'house']"
                    />
                    Recruitments
                </p>
            </template>

            <template #second-tab> Files </template>

            <template #title>Files</template>
            <template #description
                >This is the layout of the Files of the website</template
            >
        </TableCrud>
    </AppLayout>
</template>
