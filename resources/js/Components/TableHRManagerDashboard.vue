<script setup>
import { ref } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";

const props = defineProps({
    events: Array,
});

const handleDateSelect = () => {
    alert("Handle Data Select was clicked");
};

const handleEventClick = () => {
    alert("Handle Event was clicked");
};

const calendarOptions = ref({
    plugins: [timeGridPlugin],
    initialView: "timeGridWeek",
    slotMinTime: "8:00:00",
    slotMaxTime: "19:00:00",
    allDaySlot: false,
    editable: true,
    selectable: true,
    selectMirror: true,
    dayMaxEvents: true,
    select: handleDateSelect,
    eventClick: handleEventClick,

    headerToolbar: {
        left: "prev,next",
        center: "title",
        right: "timeGridWeek,timeGridDay", // user can switch between the two
    },
    weekends: false,
    events: props.events,
});

const toggleWeekends = () => {
    calendarOptions.value.weekends = !calendarOptions.value.weekends;
};
</script>

<template>
    <div
        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800"
    >
        <!-- Card header -->
        <div class="items-center justify-between lg:flex">
            <div class="mb-4 lg:mb-0">
                <h3
                    class="mb-2 text-xl font-bold text-gray-900 dark:text-white"
                >
                    <slot name="title"></slot>
                </h3>
                <span
                    class="text-base font-normal text-gray-500 dark:text-gray-400"
                    ><slot name="description"></slot
                ></span>
            </div>
        </div>
        <!-- Table -->
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto rounded-lg">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow sm:rounded-lg"></div>
                    <FullCalendar :options="calendarOptions" />
                </div>
            </div>
        </div>
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
</template>
