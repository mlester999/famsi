<script setup>
const props = defineProps({
    on: Boolean,
    id: String,
});
</script>

<template>
    <Teleport to="body">
        <transition
            enter-from-class="opacity-0 translate-x-72"
            enter-to-class="opacity-100 translate-x-0"
            enter-active-class="transition ease-in duration-200"
            leave-active-class="transition ease-in duration-1000"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                :id="id"
                v-if="on"
                class="fixed top-0 right-0 mt-6 mr-8 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-xl"
                role="alert"
            >
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg"
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
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ml-3 text-sm font-normal">
                    <slot />
                </div>
                <button
                    @click="closeMessage"
                    type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
                    data-dismiss-target="#toast-success"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
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
                </button>
            </div>
        </transition>
    </Teleport>
</template>
