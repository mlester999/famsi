<template>
    <div
        class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen pt:mt-0 dark:bg-gray-900"
    >
        <div class="mb-8">
            <slot name="logo" />
        </div>

        <div
            class="w-full max-w-xl p-6 space-y-6 sm:p-8 bg-white rounded-lg shadow dark:bg-gray-800 overflow-hidden"
        >
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                <slot name="title" />
            </h2>

            <p class="text-base font-normal text-gray-500 dark:text-gray-400">
                <slot name="description" />
            </p>
            <slot />
        </div>
    </div>
</template>
