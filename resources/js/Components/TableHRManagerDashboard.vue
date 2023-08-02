<script setup>
import { computed, ref } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import PopupCalendarModal from "./PopupCalendarModal.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    events: Array,
});

const form = useForm({
    title: "",
    startDate: "",
    endDate: "",
});

const optionsModalVisibility = ref(false);

const formattedStartTime = computed(() => {
    const [datePart, timePart] = form.startDate.value.split("T");
    const [time, timezoneOffset] = timePart.split(/[+|-]/);
    const [hours, minutes] = time.split(":");
    const amPm = hours >= 12 ? "PM" : "AM";
    const formattedHours = hours % 12 || 12;
    return `${formattedHours}:${minutes}${amPm}`;
});

const formattedEndTime = computed(() => {
    const [datePart, timePart] = form.endDate.value.split("T");
    const [time, timezoneOffset] = timePart.split(/[+|-]/);
    const [hours, minutes] = time.split(":");
    const amPm = hours >= 12 ? "PM" : "AM";
    const formattedHours = hours % 12 || 12;
    return `${formattedHours}:${minutes}${amPm}`;
});

const hideOptionsModal = () => {
    document.body.classList.remove("overflow-hidden");

    optionsModalVisibility.value = false;
};

const showOptionsModal = () => {
    document.body.classList.add("overflow-hidden");

    optionsModalVisibility.value = true;
};

const handleDateSelect = (arg) => {
    console.log(arg);
    alert("Handle Data Select was clicked");
};

const handleEventClick = (arg) => {
    form.title = arg.event.title;
    form.startDate = arg.event.startStr;
    form.endDate = arg.event.endStr;
    showOptionsModal();
    console.log(arg);
};

const handleEventDrop = (arg) => {
    console.log(arg);
    alert("Event Dropped was clicked");
};

const handleEventResize = (arg) => {
    console.log(arg);
    alert("Event Resize was clicked");
};

const calendarOptions = ref({
    plugins: [timeGridPlugin, interactionPlugin],
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
    eventDrop: handleEventDrop,
    eventResize: handleEventResize,
    eventResizableFromStart: true,
    headerToolbar: {
        left: "prev,next",
        center: "title",
        right: "timeGridWeek,timeGridDay", // user can switch between the two
    },
    weekends: false,
    events: props.events,
});
</script>

<template>
    <Transition
        enter-active-class="transition ease-in-out duration-300"
        leave-active-class="transition ease-in-out duration-300"
    >
        <Teleport to="body">
            <div
                v-if="optionsModalVisibility"
                @click="hideOptionsModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>
        </Teleport>
    </Transition>
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

    <!-- Deactivation Product Drawer -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="optionsModalVisibility"
            id="drawer-delete-product-default"
            class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto bg-white dark:bg-gray-800"
            tabindex="-1"
            aria-labelledby="drawer-label"
            aria-hidden="true"
        >
            <h5
                id="drawer-label"
                class="inline-flex items-center text-sm font-semibold text-gray-500 uppercase dark:text-gray-400"
            >
                Select Options
            </h5>
            <button
                @click="hideOptionsModal"
                type="button"
                aria-controls="drawer-delete-product-default"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
            >
                <svg
                    aria-hidden="true"
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>

            <h3 class="mb-10 mt-8 text-lg text-gray-500 dark:text-gray-400">
                <span class="font-bold text-black">Time: </span>
                <span>{{ formattedStartTime }} - {{ formattedEndTime }}</span>
            </h3>

            <h3 class="mb-10 mt-8 text-lg text-gray-500 dark:text-gray-400">
                <span class="font-bold text-black">Title: </span>
                <span>{{ form.title }}</span>
            </h3>

            <button
                class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-blue-900"
            >
                Edit
            </button>

            <button
                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900"
            >
                Delete
            </button>
        </div>
    </Transition>
</template>
