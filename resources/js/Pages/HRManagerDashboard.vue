<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import TableDashboard from "@/Components/TableDashboard.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <TableDashboard name="schedules">
                <template #title>Schedules</template>
                <template #description
                    >This is the schedule for online availability</template
                >
            </TableDashboard>
        </div>
    </AppLayout>
</template>
