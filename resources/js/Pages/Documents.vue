<script setup>
import TableCrud from "@/Components/TableCrud.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    documents: Object,
    pagination: Object,
    filters: Object,
});
</script>

<template>
    <AppLayout title="Documents">
        <TableCrud name="documents">
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

            <template #second-tab> Documents </template>

            <template #title>Documents</template>
            <template #description
                >This is the layout of the Documents of the website</template
            >
        </TableCrud>
    </AppLayout>
</template>
