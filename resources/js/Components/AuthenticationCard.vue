<template>
    <div
        class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900"
    >
        <div class="mb-8">
            <slot name="logo" />
        </div>

        <div
            class="w-full max-w-xs md:max-w-xl p-8 space-y-8 bg-white sm:rounded-lg shadow-md overflow-hidden dark:bg-gray-800"
        >
            <slot />
        </div>
    </div>
</template>
