<script setup>
import { ref } from "vue";

import { Head } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import AppNavbar from "./Partials/AppNavbar.vue";
import AppSidebar from "./Partials/AppSidebar.vue";

defineProps({
    title: String,
});

const users = ref({
    applicant: 0,
    hr_staff: 1,
    hr_manager: 2,
    admin: 3,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navbar -->
            <AppNavbar :users="users" />

            <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
                <AppSidebar :users="users" />

                <!-- Page Content -->
                <div
                    class="relative w-full h-auto overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900"
                >
                    <main>
                        <slot />
                    </main>
                </div>
            </div>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
        </div>
    </div>
</template>
