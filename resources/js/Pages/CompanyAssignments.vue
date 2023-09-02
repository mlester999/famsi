<script setup>
import TableJobs from "@/Components/TableJobs.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    companyAssignments: Object,
    pagination: Object,
    filters: Object,
});
</script>

<template>
    <AppLayout title="Company Assignments">
        <TableJobs
            :roles="companyAssignments"
            :pagination="pagination"
            :filters="filters"
            linkName="company-assignments"
            title="Company Assignment"
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

            <template #second-tab> Company Assignments </template>

            <template #title>Company Assignments</template>
            <template #description
                >This is the layout of the Company Assignments of the
                website</template
            >
        </TableJobs>
    </AppLayout>
</template>
