<script setup>
import TableCrud from "@/Components/TableCrud.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <TableCrud name="homepage">
            <template #first-tab>    
                
                <p class="inline-flex items-center text-gray-700 dark:text-gray-300">
                    <font-awesome-icon
                    class="w-5 h-5 mr-2.5"
                                    :icon="['fas', 'house']"
                                />

                    Menu
                </p>
            </template>

            <template #second-tab>
                Home Page
            </template>

            <template #title>Home Page</template>
            <template #description
                >This is the layout of the Home Page of the website</template
            >
        </TableCrud>
    </AppLayout>
</template>
