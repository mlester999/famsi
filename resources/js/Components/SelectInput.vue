<script setup>
import { onMounted } from "vue";
import { Select } from "tw-elements";

const props = defineProps({
    id: String,
    modelValue: String,
    label: String,
});

onMounted(() => {
    const options = {
        selectFilter: true,
        selectSize: "lg",
        selectClearButton: true,
    };
    const myInput = new Select(document.getElementById(props.id), options);
});
</script>

<template>
    <select
        data-te-select-init
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :id="props.id"
    >
        <slot />
    </select>
    <label data-te-select-label-ref :for="props.id">{{ props.label }} </label>

    <!-- <label
        :for="id"
        class="block text-sm font-medium text-gray-900 dark:text-white"
        >{{ labelValue }}</label
    >
    <select
        :id="id"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
    >
        <slot />
    </select> -->
</template>
