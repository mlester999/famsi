<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableHRManagerDashboard from "@/Components/TableHRManagerDashboard.vue";

defineProps({
    events: Array,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <TableHRManagerDashboard name="schedules" :events="events">
                <template #title>Schedules for Applicant Interviews</template>
                <template #description
                    >This is your schedules for applicant Interviews</template
                >
            </TableHRManagerDashboard>
        </div>
    </AppLayout>
</template>
