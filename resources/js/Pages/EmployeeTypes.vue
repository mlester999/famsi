<script setup>
import TableEmployeeTypes from "@/Components/TableEmployeeTypes.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    employeeTypes: Object,
    pagination: Object,
    filters: Object,
    jobTypes: Object,
});
</script>

<template>
    <AppLayout title="Employee Types">
        <TableEmployeeTypes
            :roles="employeeTypes"
            :pagination="pagination"
            :filters="filters"
            :jobTypes="jobTypes"
            linkName="employee-types"
            title="Employee Type"
        >
            <template #first-tab>
                <p
                    class="inline-flex items-center text-gray-700 dark:text-gray-300"
                >
                    <font-awesome-icon
                        class="w-5 h-5 mr-2.5"
                        :icon="['fas', 'house']"
                    />
                    Job Related
                </p>
            </template>

            <template #second-tab> Employee Types </template>

            <template #title>Employee Types</template>
            <template #description
                >This is the layout of the Employee Types of the
                website</template
            >
        </TableEmployeeTypes>
    </AppLayout>
</template>
