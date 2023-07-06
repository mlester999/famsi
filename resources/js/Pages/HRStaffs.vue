<script setup>
import TableCrud from "@/Components/TableCrud.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="HR Staffs">
        <TableCrud name="hr-staffs">
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

            <template #second-tab> HR Staffs </template>

            <template #title>HR Staffs</template>
            <template #description
                >This is the layout of the HR Staffs of the website</template
            >
        </TableCrud>
    </AppLayout>
</template>
