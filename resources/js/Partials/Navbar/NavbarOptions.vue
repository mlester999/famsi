<script setup>
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import DropdownLink from "@/Components/DropdownLink.vue";

defineProps({
    users: Object,
});

const page = usePage();

const userFullName = computed(() => {
    return `${page.props.user.details.first_name} ${page.props.user.details.last_name}`;
});

const userEmail = computed(() => {
    return `${page.props.auth.user.email}`;
});

const showOptionsDropdown = ref(false);

// Toggle Options Dropdown
const toggleOptionsDropdown = () => {
    showOptionsDropdown.value = !showOptionsDropdown.value;
};

const hideOptionsDropdown = () => {
    showOptionsDropdown.value = false;
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="relative">
        <button
            @click="toggleOptionsDropdown"
            v-click-outside="hideOptionsDropdown"
            type="button"
            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button-2"
            aria-expanded="false"
            data-dropdown-toggle="dropdown-2"
        >
            <span class="sr-only">Open user menu</span>
            <img
                class="w-8 h-8 rounded-full"
                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                alt="user photo"
            />
        </button>

        <div
            class="z-50 my-4 right-0 text-base list-none divide-y divide-gray-200 bg-white rounded shadow dark:bg-gray-700 dark:divide-gray-600"
            :class="{
                hidden: !showOptionsDropdown,
            }"
            id="dropdown-2"
            style="position: absolute"
        >
            <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                    {{ userFullName }}
                </p>
                <p
                    class="text-sm font-medium text-gray-900 truncate dark:text-gray-300"
                    role="none"
                >
                    {{ userEmail }}
                </p>
            </div>
            <ul class="py-1" role="none">
                <DropdownLink
                    v-if="$page.props.auth.user.user_type == users.applicant"
                    :href="route('applicant.profile.show')"
                >
                    Profile
                </DropdownLink>

                <DropdownLink
                    v-if="$page.props.auth.user.user_type == users.hr_staff"
                    :href="route('hr-staff.profile.show')"
                >
                    Profile
                </DropdownLink>

                <DropdownLink
                    v-if="$page.props.auth.user.user_type == users.hr_manager"
                    :href="route('hr-manager.profile.show')"
                >
                    Profile
                </DropdownLink>

                <DropdownLink
                    v-if="$page.props.auth.user.user_type == users.admin"
                    :href="route('admin.profile.show')"
                >
                    Profile
                </DropdownLink>

                <div />

                <!-- Authentication -->
                <form @submit.prevent="logout">
                    <DropdownLink as="button"> Log Out </DropdownLink>
                </form>
            </ul>
        </div>
    </div>
</template>
