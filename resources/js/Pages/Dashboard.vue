<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DashboardCard from "@/Components/DashboardCard.vue";
import TableDashboard from "@/Components/TableDashboard.vue";

defineProps({
    totalApplicants: Number,
    totalUserApplicants: Number,
    totalHrStaffs: Number,
    totalHrManagers: Number,
    totalQualifications: Number,
    totalBenefits: Number,
    totalCompanyAssignments: Number,
    totalJobTypes: Number,
    totalEmployeeTypes: Number,
    totalIndustries: Number,
    totalJobPositions: Number,
    totalActivityLogs: Number,
    totalDisqualified: Number,
    totalApplications: Number,
    totalForInterview: Number,
    totalInProgress: Number,
    totalQualified: Number,
    totalDisqualified: Number,
    totalHired: Number,
    totalDocuments: Number,
});

function isNumber(value) {
      return typeof value === 'number' && !isNaN(value);
    }

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="px-4 pt-6">
            <div
                class="grid w-full grid-cols-1 gap-4 my-4 xl:grid-cols-2 2xl:grid-cols-3"
            >
                <DashboardCard v-if="isNumber(totalApplicants)">
                    <template #label> Total Hired Applicants </template>

                    <template #title> {{ totalApplicants }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalHrStaffs)">
                    <template #label> Total HR Staffs </template>

                    <template #title> {{ totalHrStaffs }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalUserApplicants)">
                    <template #label> Total Applicants </template>

                    <template #title> {{ totalUserApplicants }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalHrManagers)">
                    <template #label> Total HR Managers </template>

                    <template #title> {{ totalHrManagers }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalQualifications)">
                    <template #label> Total Qualifications </template>

                    <template #title> {{ totalQualifications }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalBenefits)">
                    <template #label> Total Benefits </template>

                    <template #title> {{ totalBenefits }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalCompanyAssignments)">
                    <template #label> Total Company Assignments </template>

                    <template #title> {{ totalCompanyAssignments }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalJobTypes)">
                    <template #label> Total Job Types </template>

                    <template #title> {{ totalJobTypes }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalEmployeeTypes)">
                    <template #label> Total Employee Types </template>

                    <template #title> {{ totalEmployeeTypes }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalIndustries)">
                    <template #label> Total Industries </template>

                    <template #title> {{ totalIndustries }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalJobPositions)">
                    <template #label> Total Job Positions </template>

                    <template #title> {{ totalJobPositions }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalActivityLogs)">
                    <template #label> Total Activity Logs </template>

                    <template #title> {{ totalActivityLogs }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalApplications)">
                    <template #label> Total Applications </template>

                    <template #title> {{ totalApplications }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalDisqualified)">
                    <template #label> Total Disqualified </template>

                    <template #title> {{ totalDisqualified }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalForInterview)">
                    <template #label> Total For Interview </template>

                    <template #title> {{ totalForInterview }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalInProgress)">
                    <template #label> Total In Progress </template>

                    <template #title> {{ totalInProgress }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalQualified)">
                    <template #label> Total Qualified </template>

                    <template #title> {{ totalQualified }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalHired)">
                    <template #label> Total Hired </template>

                    <template #title> {{ totalHired }} </template>
                </DashboardCard>

                <DashboardCard v-if="isNumber(totalDocuments)">
                    <template #label> Total Documents </template>

                    <template #title> {{ totalDocuments }} </template>
                </DashboardCard>
                
            </div>
            <p class="my-10 text-sm text-center text-gray-500">
        © 2023
        <a
            href="https://www.facebook.com/FAMSILaguna/"
            class="hover:underline"
            target="_blank"
            >Fully Advanced Manpower Solutions, Inc.</a
        >. All rights reserved.
    </p>
        </div>
    </AppLayout>
</template>
