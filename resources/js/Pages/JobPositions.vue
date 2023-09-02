<script setup>
import TableJobPositions from "@/Components/TableJobPositions.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    jobPositions: Object,
    pagination: Object,
    filters: Object,
    companyAssignments: Object,
});
</script>

<template>
    <AppLayout title="Job Positions">
        <TableJobPositions
            :roles="jobPositions"
            :pagination="pagination"
            :filters="filters"
            :companyAssignments="companyAssignments"
            linkName="job-positions"
            title="Job Position"
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

            <template #second-tab> Job Positions </template>

            <template #title>Job Positions</template>
            <template #description
                >This is the layout of the Job Positions of the
                website</template
            >
        </TableJobPositions>
    </AppLayout>
</template>
