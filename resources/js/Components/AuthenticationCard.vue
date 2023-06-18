<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100"
    >
        <div>
            <slot name="logo" />
        </div>

        <div class="mx-auto sm:w-full sm:max-w-sm">
            <slot name="title" />
        </div>

        <div
            class="w-full max-w-xs md:max-w-xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
