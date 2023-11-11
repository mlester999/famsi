<script setup>
import Notification from "@/Components/Notification.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const showNotificationsDropdown = ref(false);

// Toggle Notification Dropdown
const toggleNotificationsDropdown = () => {
    showNotificationsDropdown.value = !showNotificationsDropdown.value;
};

const hideNotificationsDropdown = () => {
    showNotificationsDropdown.value = false;
};

const page = usePage();

const notifications = computed(() => {
    return page.props.user.notifications;
});
</script>

<template>
    <div class="relative">
        <button
            @click="toggleNotificationsDropdown"
            v-click-outside="hideNotificationsDropdown"
            type="button"
            data-dropdown-toggle="notification-dropdown"
            class="py-1.5 px-2.5 text-gray-500 group rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700"
        >
            <span class="sr-only">View notifications</span>

            <svg
                class="w-6 h-6 text-gray-500 transition duration-0 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                ></path>
            </svg>
        </button>

        <div
            class="z-50 max-w-sm right-0 w-max my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:divide-gray-600 dark:bg-gray-700"
            :class="{
                hidden: !showNotificationsDropdown,
            }"
            id="notification-dropdown"
            style="position: absolute"
            data-popper-placement="bottom"
        >
            <div
                class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
            >
                Notifications
            </div>
            <div>
                <div
                    v-if="notifications.length === 0"
                    class="flex px-4 py-3 border-b dark:border-gray-600"
                >
                    <div class="w-full pl-3">
                        <div
                            class="text-gray-700 font-normal text-sm mb-1.5 dark:text-gray-200"
                        >
                            There's no notification right now...
                        </div>
                    </div>
                </div>
                <Notification :notifications="notifications" />
            </div>
        </div>
    </div>
</template>
