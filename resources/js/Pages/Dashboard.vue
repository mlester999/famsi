<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DashboardCard from "@/Components/DashboardCard.vue";
import TableDashboard from "@/Components/TableDashboard.vue";

defineProps({
    totalApplicants: Number,
    totalHrStaffs: Number,
    totalHrManagers: Number,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <div
                class="grid w-full grid-cols-1 gap-4 my-4 xl:grid-cols-2 2xl:grid-cols-3"
            >
                <DashboardCard>
                    <template #label> Total Applicants </template>

                    <template #title> {{ totalApplicants }} </template>
                </DashboardCard>

                <DashboardCard>
                    <template #label> Total HR Staffs </template>

                    <template #title> {{ totalHrStaffs }} </template>
                </DashboardCard>

                <DashboardCard>
                    <template #label> Total HR Managers </template>

                    <template #title> {{ totalHrManagers }} </template>
                </DashboardCard>
            </div>

            <TableDashboard name="schedules">
                <template #title>Schedules</template>
                <template #description
                    >This is the schedule for online availability</template
                >
            </TableDashboard>
        </div>
    </AppLayout>
</template>
