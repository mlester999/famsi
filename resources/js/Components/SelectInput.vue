<script setup>
import { onMounted } from "vue";
import { Select } from "tw-elements";

const props = defineProps({
    id: String,
    modelValue: String,
    label: String,
    error: String,
    canSearch: Boolean,
});

onMounted(() => {
    const options = {
        selectFilter: props.canSearch ?? true,
        selectSize: "lg",
        selectClearButton: false,
    };
    const myInput = new Select(document.getElementById(props.id), options);
});
</script>

<template>
    <select
        data-te-select-init
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        :id="props.id"
    >
        <slot />
    </select>
    <label data-te-select-label-ref :for="props.id">{{ props.label }} </label>

    <p class="text-red-500 text-xs mt-1 absolute" v-if="error">
        {{ error }}
    </p>
</template>
