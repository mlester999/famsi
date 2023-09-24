<script setup>
import TableJobs from "@/Components/TableJobs.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    jobTypes: Object,
    pagination: Object,
    filters: Object,
});
</script>

<template>
    <AppLayout title="Job Types">
        <TableJobs
            :roles="jobTypes"
            :pagination="pagination"
            :filters="filters"
            linkName="job-types"
            title="Job Type"
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

            <template #second-tab> Job Types </template>

            <template #title>Job Types</template>
            <template #description
                >This is the layout of the Job Types of the website</template
            >
        </TableJobs>
    </AppLayout>
</template>
