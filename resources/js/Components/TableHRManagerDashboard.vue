<script setup>
import { computed, ref } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import { format } from "date-fns";
import { useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    events: Array,
    applicants: Array,
    linkName: String,
});

const page = usePage();

const form = useForm({
    title: "",
    applicant: "",
    date: "",
    day: "",
    startTime: "",
    endTime: "",
    startTimeDate: "",
    endTimeDate: "",
});

const currentScheduleId = ref("");

const storeSchedule = () => {
    form.post(
        `/${page.props.user.role}/${props.linkName}/store/${currentScheduleId.value}`,
        {
            onSuccess: () => {
                hideAddScheduleModal();
            },
        }
    );
};

const deleteSchedule = () => {
    form.delete(
        `/${page.props.user.role}/${props.linkName}/delete/${currentScheduleId.value}`,
        {
            onSuccess: () => {
                hideDeleteScheduleModal();
            },
        }
    );
};

const optionsModalVisibility = ref(false);
const deleteScheduleModalVisibility = ref(false);
const addScheduleModalVisibility = ref(false);

const hideOptionsModal = () => {
    document.body.classList.remove("overflow-hidden");

    optionsModalVisibility.value = false;

    form.reset();
    form.clearErrors();
};

const showOptionsModal = (arg) => {
    document.body.classList.add("overflow-hidden");

    optionsModalVisibility.value = true;

    currentScheduleId.value = arg.event.id;
    form.title = arg.event.title;
    form.date = format(new Date(arg.event.endStr), "MMMM d, yyyy");
    form.day = format(new Date(arg.event.endStr), "EEEE");
    form.startTime = format(new Date(arg.event.startStr), "h:mm a");
    form.endTime = format(new Date(arg.event.endStr), "h:mm a");
};

// Show and Hide the Delete Schedule Modal
const hideDeleteScheduleModal = () => {
    document.body.classList.remove("overflow-hidden");

    deleteScheduleModalVisibility.value = false;

    form.reset();
    form.clearErrors();
};

const showDeleteScheduleModal = () => {
    optionsModalVisibility.value = false;

    deleteScheduleModalVisibility.value = true;
};

// Show and Hide the Add Schedule Modal
const hideAddScheduleModal = () => {
    document.body.classList.remove("overflow-hidden");

    addScheduleModalVisibility.value = false;

    form.reset();
    form.clearErrors();
};

const showAddScheduleModal = (arg) => {
    console.log(arg);
    addScheduleModalVisibility.value = true;

    form.date = format(new Date(arg.endStr), "MMMM d, yyyy");
    form.day = format(new Date(arg.endStr), "EEEE");
    form.startTime = format(new Date(arg.startStr), "h:mm a");
    form.endTime = format(new Date(arg.endStr), "h:mm a");
    form.startTimeDate = arg.startStr;
    form.endTimeDate = arg.endStr;
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
    select: showAddScheduleModal,
    eventClick: showOptionsModal,
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

            <div
                v-if="deleteScheduleModalVisibility"
                @click="hideDeleteScheduleModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>

            <div
                v-if="addScheduleModalVisibility"
                @click="hideAddScheduleModal"
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

    <!-- Options Modal Modal -->
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

            <div class="px-4 py-8 space-y-4">
                <h3 class="text-lg text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-black">Date: </span>
                    <span>{{ form.date }}</span>
                </h3>

                <h3 class="text-lg text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-black">Day: </span>
                    <span>{{ form.day }}</span>
                </h3>

                <h3 class="text-lg text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-black">Time: </span>
                    <span>{{ form.startTime }} - {{ form.endTime }}</span>
                </h3>

                <h3 class="text-lg text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-black">Title: </span>
                    <span>{{ form.title }}</span>
                </h3>
            </div>

            <div class="flex justify-end">
                <button
                    class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-blue-900"
                >
                    Edit
                </button>

                <button
                    @click="showDeleteScheduleModal"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900"
                >
                    Delete
                </button>
            </div>
        </div>
    </Transition>

    <!-- Delete Schedule Modal -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="deleteScheduleModalVisibility"
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
                Delete
            </h5>
            <button
                @click="hideDeleteScheduleModal"
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
            <svg
                class="w-10 h-10 mt-8 mb-4 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
            </svg>
            <h3 class="mb-6 text-lg text-gray-500 dark:text-gray-400">
                Are you sure you want to delete this schedule?
            </h3>

            <div class="flex justify-end">
                <form @submit.prevent="deleteSchedule" class="inline-block">
                    <button
                        type="submit"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900"
                    >
                        Yes, I'm sure
                    </button>
                </form>
                <button
                    @click="hideDeleteScheduleModal"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                    data-modal-toggle="delete-product-modal"
                >
                    No, cancel
                </button>
            </div>
        </div>
    </Transition>

    <!-- Add Schedule Modal -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            id="drawer-create-product-default"
            v-if="addScheduleModalVisibility"
            class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto bg-white dark:bg-gray-800"
            tabindex="-1"
            aria-labelledby="drawer-label"
            aria-hidden="true"
        >
            <h5
                id="drawer-label"
                class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400"
            >
                Add New Schedule
            </h5>
            <button
                type="button"
                @click="hideAddScheduleModal"
                aria-controls="drawer-create-product-default"
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
                <span class="sr-only">Close</span>
            </button>

            <form @submit.prevent="storeSchedule">
                <div class="space-y-6">
                    <div>
                        <label
                            for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Title</label
                        >
                        <input
                            type="text"
                            v-model="form.title"
                            name="title"
                            id="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Schedule Title"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.title"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="gender"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Applicant (Optional)</label
                        >
                        <select
                            id="gender"
                            v-model="form.applicant"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="" selected hidden>
                                Select an Applicant
                            </option>

                            <option
                                v-for="applicant in applicants"
                                :value="applicant.id"
                            >
                                {{ applicant.first_name }}
                                {{ applicant.last_name }}
                            </option>
                        </select>
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.applicant"
                        >
                            {{ form.errors.applicant }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Date</label
                        >
                        <input
                            type="text"
                            v-model="form.date"
                            name="date"
                            id="date"
                            class="bg-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Schedule Date"
                            disabled
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.date"
                        >
                            {{ form.errors.date }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="day"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Day</label
                        >
                        <input
                            type="text"
                            v-model="form.day"
                            name="day"
                            id="day"
                            class="bg-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Schedule Day"
                            disabled
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.title"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="startTime"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Start Time</label
                        >
                        <input
                            type="text"
                            v-model="form.startTime"
                            name="startTime"
                            id="startTime"
                            class="bg-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Schedule Start Time"
                            disabled
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.startTime"
                        >
                            {{ form.errors.startTime }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="endTime"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >End Time</label
                        >
                        <input
                            type="text"
                            v-model="form.endTime"
                            name="endTime"
                            id="endTime"
                            class="bg-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Schedule End Time"
                            disabled
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.endTime"
                        >
                            {{ form.errors.endTime }}
                        </p>
                    </div>
                </div>
                <div
                    class="bottom-0 left-0 flex justify-center w-full pb-4 space-x-4 md:px-4 md:absolute"
                >
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:bg-blue-200 dark:disabled:bg-blue-900"
                    >
                        Add
                    </button>
                    <button
                        @click="hideAddScheduleModal"
                        type="button"
                        data-drawer-dismiss="drawer-create-product-default"
                        aria-controls="drawer-create-product-default"
                        class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    >
                        <svg
                            aria-hidden="true"
                            class="w-5 h-5 -ml-1 sm:mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </Transition>
</template>
