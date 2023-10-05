<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableHrStaffDashboard from "@/Components/TableHrStaffDashboard.vue";

defineProps({
    applicants: Array,
    events: Array,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <TableHrStaffDashboard
                linkName="dashboard"
                :events="events"
                :applicants="applicants"
            >
                <template #title>Schedules for Applicant Interviews</template>
                <template #description
                    >This is your schedules for applicant Interviews</template
                >
            </TableHrStaffDashboard>
        </div>
    </AppLayout>
</template>
