<script setup>
import { router, usePage, useForm } from "@inertiajs/vue3";
import { ref, watch, Transition, Teleport } from "vue";
import debounce from "lodash.debounce";

import Pagination from "../Partials/Table/Pagination.vue";

const props = defineProps({
    roles: Object,
    pagination: Object,
    filters: Object,
    linkName: String,
    title: String,
});

const form = useForm({
    first_name: "",
    middle_name: "",
    last_name: "",
    gender: "",
    email: "",
    contact_number: "",
});

const page = usePage();

let search = ref(props.filters.search);

let currentUpdatingUserID = ref(null);
let updateModalVisibility = ref(false);

let addModalVisibility = ref(false);
let activationModalVisibility = ref(false);
let deactivationModalVisibility = ref(false);

const submit = () => {
    form.post(`/${page.props.user.role}/${props.linkName}/store`, {
        onSuccess: () => {
            document.body.classList.remove("overflow-hidden");
            updateModalVisibility.value = false;
            addModalVisibility.value = false;
            form.reset();
            clearErrors();
        },
    });
};

const update = () => {
    form.put(
        `/${page.props.user.role}/${props.linkName}/update/${currentUpdatingUserID.value}`,
        {
            onSuccess: () => {
                hideUpdateModal();
                hideAddModal();
                form.reset();
                clearErrors();
            },
        }
    );
};

const activate = () => {
    form.put(
        `/${page.props.user.role}/${props.linkName}/activate/${currentUpdatingUserID.value}`,
        {
            onSuccess: () => {
                hideUpdateModal();
                hideAddModal();
                hideActivationModal();
                hideDeactivationModal();
                form.reset();
                clearErrors();
            },
        }
    );
};

const deactivate = () => {
    form.put(
        `/${page.props.user.role}/${props.linkName}/deactivate/${currentUpdatingUserID.value}`,
        {
            onSuccess: () => {
                hideUpdateModal();
                hideAddModal();
                hideActivationModal();
                hideDeactivationModal();
                form.reset();
                clearErrors();
            },
        }
    );
};

// Activation Modal
const showActivationModal = (id) => {
    document.body.classList.add("overflow-hidden");

    currentUpdatingUserID.value = id;

    activationModalVisibility.value = true;
};

const hideActivationModal = () => {
    document.body.classList.remove("overflow-hidden");

    currentUpdatingUserID.value = null;

    activationModalVisibility.value = false;
};

// Deactivation Modal
const showDeactivationModal = (id) => {
    document.body.classList.add("overflow-hidden");

    currentUpdatingUserID.value = id;

    deactivationModalVisibility.value = true;
};

const hideDeactivationModal = () => {
    document.body.classList.remove("overflow-hidden");

    currentUpdatingUserID.value = null;

    deactivationModalVisibility.value = false;
};

// Update Modal
const showUpdateModal = (data) => {
    form.first_name = data.first_name;
    form.middle_name = data.middle_name;
    form.last_name = data.last_name;
    form.gender = data.gender;
    form.email = data.email;
    form.contact_number = data.contact_number;

    document.body.classList.add("overflow-hidden");

    currentUpdatingUserID.value = data.id;

    updateModalVisibility.value = true;
};

const hideUpdateModal = () => {
    document.body.classList.remove("overflow-hidden");

    currentUpdatingUserID.value = null;

    updateModalVisibility.value = false;

    form.reset();

    form.clearErrors();
};

const showAddModal = () => {
    form.first_name = "";
    form.middle_name = "";
    form.last_name = "";
    form.gender = "";
    form.email = "";
    form.contact_number = "";

    document.body.classList.add("overflow-hidden");

    addModalVisibility.value = true;
};

const hideAddModal = () => {
    document.body.classList.remove("overflow-hidden");

    addModalVisibility.value = false;

    form.reset();

    form.clearErrors();
};

watch(
    search,
    debounce((value) => {
        const query = {};
        if (value) {
            query.search = value;
        }

        router.get(`/${page.props.user.role}/${props.linkName}`, query, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);
</script>

<template>
    <Transition
        enter-active-class="transition ease-in-out duration-300"
        leave-active-class="transition ease-in-out duration-300"
    >
        <Teleport to="body">
            <div
                v-if="updateModalVisibility"
                @click="hideUpdateModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>

            <div
                v-else-if="addModalVisibility"
                @click="hideAddModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>

            <div
                v-else-if="activationModalVisibility"
                @click="hideActivationModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>

            <div
                v-else-if="deactivationModalVisibility"
                @click="hideDeactivationModal"
                class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30 transition duration-200"
            ></div>
        </Teleport>
    </Transition>
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700"
    >
        <div class="w-full mb-1">
            <div class="mb-4">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol
                        class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2"
                    >
                        <li class="inline-flex items-center">
                            <slot name="first-tab"></slot>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-6 h-6 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <span
                                    class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500"
                                    aria-current="page"
                                    ><slot name="second-tab"></slot
                                ></span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1
                    class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                >
                    <slot name="title"></slot>
                </h1>
                <span
                    class="text-base font-normal text-gray-500 dark:text-gray-400"
                    ><slot name="description"></slot
                ></span>
            </div>
            <div
                class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700"
            >
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <input
                            v-model="search"
                            type="text"
                            name="search"
                            id="products-search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search..."
                        />
                    </div>
                </div>
                <button
                    id="addNewButton"
                    @click="showAddModal"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    type="button"
                >
                    <font-awesome-icon
                        class="mr-2 -ml-1"
                        :icon="['fas', 'plus']"
                    />
                    Add new {{ title }}
                </button>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table
                        class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600"
                    >
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400"
                                >
                                    #
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    First Name
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Middle Name
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Last Name
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Gender
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Email Address
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Contact Number
                                </th>
                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Status
                                </th>

                                <th
                                    scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
                        >
                            <tr
                                v-for="(role, index) in roles.data"
                                :key="role.id"
                                class="hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <td
                                    class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400"
                                >
                                    <div
                                        class="text-base text-center font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ role.id }}
                                    </div>
                                </td>
                                <td
                                    class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.first_name }}
                                    </div>
                                </td>
                                <td
                                    class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.middle_name }}
                                    </div>
                                </td>
                                <td
                                    class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.last_name }}
                                    </div>
                                </td>
                                <td
                                    class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.gender }}
                                    </div>
                                </td>
                                <td
                                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.email }}
                                    </div>
                                </td>
                                <td
                                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        {{ role.contact_number }}
                                    </div>
                                </td>

                                <td
                                    class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div
                                        v-if="role.is_active"
                                        class="flex items-center"
                                    >
                                        <div
                                            class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"
                                        ></div>
                                        Active
                                    </div>

                                    <div v-else class="flex items-center">
                                        <div
                                            class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"
                                        ></div>
                                        Inactive
                                    </div>
                                </td>

                                <td class="p-4 space-x-2 whitespace-nowrap">
                                    <button
                                        type="button"
                                        id="updateProductButton"
                                        @click="
                                            showUpdateModal(roles.data[index])
                                        "
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    >
                                        Update
                                    </button>
                                    <button
                                        v-if="role.is_active"
                                        @click="showDeactivationModal(role.id)"
                                        type="button"
                                        id="deactivateUserButton"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                    >
                                        Deactivate
                                    </button>

                                    <button
                                        v-else
                                        @click="showActivationModal(role.id)"
                                        type="button"
                                        id="activateUserButton"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-900"
                                    >
                                        Activate
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="roles.data.length === 0">
                                <td
                                    colspan="9"
                                    class="max-w-sm text-center p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400"
                                >
                                    <div
                                        class="text-base text-gray-900 dark:text-white"
                                    >
                                        No {{ title }} found.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div
        class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700"
    >
        <Pagination :roles="roles" :pagination="pagination" />
    </div>

    <!-- Edit Product Drawer -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="updateModalVisibility"
            id="drawer-update-product-default"
            class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto bg-white dark:bg-gray-800"
            tabindex="-1"
            aria-labelledby="drawer-label"
            aria-hidden="true"
        >
            <h5
                id="drawer-label"
                class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400"
            >
                Update {{ title }}
            </h5>
            <button
                type="button"
                @click="hideUpdateModal"
                aria-controls="drawer-update-product-default"
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
            <form @submit.prevent="update">
                <div class="space-y-8">
                    <div>
                        <label
                            for="firstName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >First Name</label
                        >
                        <input
                            type="text"
                            v-model="form.first_name"
                            name="firstName"
                            id="firstName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="First Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.first_name"
                        >
                            {{ form.errors.first_name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="firstName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Middle Name</label
                        >
                        <input
                            type="text"
                            v-model="form.middle_name"
                            name="middleName"
                            id="middleName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Middle Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.middle_name"
                        >
                            {{ form.errors.middle_name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="lastName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Last Name</label
                        >
                        <input
                            type="text"
                            v-model="form.last_name"
                            name="lastName"
                            id="lastName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Last Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.last_name"
                        >
                            {{ form.errors.last_name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="gender"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Gender</label
                        >
                        <select
                            id="gender"
                            v-model="form.gender"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="" selected hidden>
                                Select Gender
                            </option>

                            <option
                                value="Male"
                                :selected="form.gender === 'Male'"
                            >
                                Male
                            </option>
                            <option
                                value="Female"
                                :selected="form.gender === 'Female'"
                            >
                                Female
                            </option>
                        </select>
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.gender"
                        >
                            {{ form.errors.gender }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="emailAddress"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Email Address</label
                        >
                        <input
                            type="text"
                            v-model="form.email"
                            name="emailAddress"
                            id="emailAddress"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Email Address"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.email"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="emailAddress"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Contact Number</label
                        >
                        <input
                            type="text"
                            v-model="form.contact_number"
                            name="contactNumber"
                            id="contactNumber"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Contact Number"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.contact_number"
                        >
                            {{ form.errors.contact_number }}
                        </p>
                    </div>
                </div>
                <div
                    class="bottom-0 left-0 flex justify-center w-full pb-4 mt-4 space-x-4 sm:absolute sm:px-4 sm:mt-0"
                >
                    <button
                        type="submit"
                        class="w-full justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Update
                    </button>
                    <button
                        @click="hideUpdateModal"
                        type="button"
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

    <!-- Deactivation Product Drawer -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="deactivationModalVisibility"
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
                Deactivate
            </h5>
            <button
                @click="hideDeactivationModal"
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
                Are you sure you want to deactivate this {{ title }}?
            </h3>

            <form @submit.prevent="deactivate" class="inline-block">
                <button
                    type="submit"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900"
                >
                    Yes, I'm sure
                </button>
            </form>
            <button
                @click="hideDeactivationModal"
                class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                data-modal-toggle="delete-product-modal"
            >
                No, cancel
            </button>
        </div>
    </Transition>

    <!-- Activation Product Drawer -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="activationModalVisibility"
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
                Activate
            </h5>
            <button
                @click="hideActivationModal"
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
                class="w-10 h-10 mt-8 mb-4 text-green-600"
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
                Are you sure you want to activate this {{ title }}?
            </h3>
            <form @submit.prevent="activate" class="inline-block">
                <button
                    type="submit"
                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-green-900"
                >
                    Yes, I'm sure
                </button>
            </form>
            <button
                @click="hideActivationModal"
                class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
            >
                No, cancel
            </button>
        </div>
    </Transition>

    <!-- Add Product Drawer -->
    <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform translate-x-0"
        leave-active-class="transition-transform translate-x-0"
        leave-to-class="translate-x-full"
    >
        <div
            id="drawer-create-product-default"
            v-if="addModalVisibility"
            class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto bg-white dark:bg-gray-800"
            tabindex="-1"
            aria-labelledby="drawer-label"
            aria-hidden="true"
        >
            <h5
                id="drawer-label"
                class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400"
            >
                New {{ title }}
            </h5>
            <button
                type="button"
                @click="hideAddModal"
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
            <form @submit.prevent="submit">
                <div class="space-y-8">
                    <div>
                        <label
                            for="firstName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >First Name</label
                        >
                        <input
                            type="text"
                            v-model="form.first_name"
                            name="firstName"
                            id="firstName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="First Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.first_name"
                        >
                            {{ form.errors.first_name }}
                        </p>
                    </div>
                    <div class="mb-10">
                        <label
                            for="middleName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Middle Name</label
                        >
                        <input
                            type="text"
                            v-model="form.middle_name"
                            name="middleName"
                            id="middleName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Middle Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.middle_name"
                        >
                            {{ form.errors.middle_name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="lastName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Last Name</label
                        >
                        <input
                            type="text"
                            v-model="form.last_name"
                            name="lastName"
                            id="lastName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Last Name"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.last_name"
                        >
                            {{ form.errors.last_name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="gender"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Gender</label
                        >
                        <select
                            id="gender"
                            v-model="form.gender"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="" selected hidden>
                                Select Gender
                            </option>

                            <option
                                value="Male"
                                :selected="form.gender === 'Male'"
                            >
                                Male
                            </option>
                            <option
                                value="Female"
                                :selected="form.gender === 'Female'"
                            >
                                Female
                            </option>
                        </select>
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.gender"
                        >
                            {{ form.errors.gender }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="emailAddress"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Email Address</label
                        >
                        <input
                            type="text"
                            v-model="form.email"
                            name="emailAddress"
                            id="emailAddress"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Email Address"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.email"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="emailAddress"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Contact Number</label
                        >
                        <input
                            type="text"
                            v-model="form.contact_number"
                            name="contactNumber"
                            id="contactNumber"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Contact Number"
                        />
                        <p
                            class="text-red-500 text-xs mt-1 absolute"
                            v-if="form.errors.contact_number"
                        >
                            {{ form.errors.contact_number }}
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
                        @click="hideAddModal"
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
