<script setup>
import TableLogs from "@/Components/TableLogs.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    activityLogs: Object,
    pagination: Object,
    filters: Object,
});
</script>

<template>
    <AppLayout title="Activity Logs">
        <TableLogs
            :logs="activityLogs"
            :pagination="pagination"
            :filters="filters"
            linkName="activity-logs"
            title="Activity Log"
        >
            <template #first-tab>
                <p
                    class="inline-flex items-center text-gray-700 dark:text-gray-300"
                >
                    <font-awesome-icon
                        class="w-5 h-5 mr-2.5"
                        :icon="['fas', 'house']"
                    />
                    Logs
                </p>
            </template>

            <template #second-tab> Activity Logs </template>

            <template #title>Activity Logs</template>
            <template #description
                >This section pertains to activity logs.</template
            >
        </TableLogs>
    </AppLayout>
</template>
