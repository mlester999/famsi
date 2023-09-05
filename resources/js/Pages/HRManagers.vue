<script setup>
import TableCrud from "@/Components/TableCrud.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    hrManagers: Object,
    pagination: Object,
    filters: Object,
});
</script>

<template>
    <AppLayout title="HR Managers">
        <TableCrud
            :roles="hrManagers"
            :pagination="pagination"
            :filters="filters"
            linkName="hr-managers"
            title="HR Manager"
        >
            <template #first-tab>
                <p
                    class="inline-flex items-center text-gray-700 dark:text-gray-300"
                >
                    <font-awesome-icon
                        class="w-5 h-5 mr-2.5"
                        :icon="['fas', 'house']"
                    />
                    Employees
                </p>
            </template>

            <template #second-tab> HR Managers </template>

            <template #title>HR Managers</template>
            <template #description
                >This section pertains to HR Manager accounts.</template
            >
        </TableCrud>
    </AppLayout>
</template>
