<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DashboardCard from "@/Components/DashboardCard.vue";
import TableDashboard from "@/Components/TableDashboard.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <div
                class="grid w-full grid-cols-1 gap-4 my-4 xl:grid-cols-2 2xl:grid-cols-3"
            >
                <DashboardCard>
                    <template #label> Total Applicants </template>

                    <template #title> 1,123 </template>
                </DashboardCard>

                <DashboardCard>
                    <template #label> Total HR Staffs </template>

                    <template #title> 16 </template>
                </DashboardCard>

                <DashboardCard>
                    <template #label> Pending Applications </template>

                    <template #title> 692 </template>
                </DashboardCard>
            </div>

            <TableDashboard name="transactions">
                <template #title>Transactions</template>
                <template #description
                    >This is a list of latest transactions</template
                >
            </TableDashboard>
        </div>
    </AppLayout>
</template>
