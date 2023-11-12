<script setup>
import { formatDistanceToNow } from "date-fns";

const props = defineProps({
    notifications: Array,
});
</script>

<template>
    <div class="max-h-[600px] overflow-y-auto">
        <div
            v-for="notification in notifications.slice().reverse().slice(0, 5)"
            :key="notification.id"
            class="flex px-4 py-3 border-b dark:border-gray-600"
        >
            <!-- <div class="flex-shrink-0">
                <img
                    class="rounded-full w-11 h-11"
                    src="/images/avatar.png"
                    alt="User Avatar"
                />
            </div> -->
            <div class="w-full pl-3">
                <div
                    class="text-gray-700 font-normal text-sm mb-1.5 dark:text-gray-200"
                >
                    {{ notification.title }}
                </div>
                <div
                    class="text-xs font-medium text-primary-700 dark:text-primary-400"
                >
                    {{ formatDistanceToNow(new Date(notification.created_at)) }}
                    ago
                </div>
            </div>
        </div>
    </div>
</template>
