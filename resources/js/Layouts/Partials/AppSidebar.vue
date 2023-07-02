<script setup>
import SidebarCategory from "@/Components/SidebarCategory.vue";
import SidebarTab from "@/Components/SidebarTab.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps({
    users: Object,
});

const page = usePage();

const currentUserDashboard = computed(() => {
    if (page.url.includes("admin")) {
        return "admin.dashboard";
    }

    if (page.url.includes("manager")) {
        return "hr-manager.dashboard";
    }

    if (page.url.includes("staff")) {
        return "hr-staff.dashboard";
    }

    if (page.url.includes("applicant")) {
        return "applicant.dashboard";
    }
});
</script>

<template>
    <aside
        id="sidebar"
        class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-0 lg:flex transition-width"
        aria-label="Sidebar"
    >
        <div
            class="relative flex flex-col flex-1 min-h-0 py-5 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
        >
            <SidebarCategory name="Menu">
                <template #tab>
                    <li>
                        <SidebarTab
                            :href="route(currentUserDashboard)"
                            :class="[
                                $page.url.includes('dashboard') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'gauge']"
                                />
                            </template>

                            <template #title> Dashboard </template>
                        </SidebarTab>
                    </li>

                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('home-page') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'house']"
                                />
                            </template>

                            <template #title> Home Page </template>
                        </SidebarTab>
                    </li>

                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('announcement') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'scroll']"
                                />
                            </template>

                            <template #title> Announcement </template>
                        </SidebarTab>
                    </li>
                </template>
            </SidebarCategory>

            <SidebarCategory name="Employees">
                <template #tab>
                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('manager') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'user-shield']"
                                />
                            </template>

                            <template #title> HR Admin </template>
                        </SidebarTab>
                    </li>

                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('home-page') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'user']"
                                />
                            </template>

                            <template #title> HR Staffs </template>
                        </SidebarTab>
                    </li>
                </template>
            </SidebarCategory>

            <SidebarCategory name="Recruitment">
                <template #tab>
                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('manager') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'user-check']"
                                />
                            </template>

                            <template #title> Applicants </template>
                        </SidebarTab>
                    </li>

                    <li>
                        <SidebarTab
                            href="#"
                            :class="[
                                $page.url.includes('home-page') &&
                                    'bg-gray-100 dark:bg-gray-700',
                            ]"
                        >
                            <template #icon>
                                <font-awesome-icon
                                    class="w-6 h-6 mr-3 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    :icon="['fas', 'file']"
                                />
                            </template>

                            <template #title> Files </template>
                        </SidebarTab>
                    </li>
                </template>
            </SidebarCategory>
        </div>
    </aside>

    <div
        class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90"
        id="sidebarBackdrop"
    ></div>
</template>
