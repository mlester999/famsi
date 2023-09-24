<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputField from "@/Components/InputField.vue";

const form = useForm({
    firstName: "",
    middleName: "",
    lastName: "",
    email: "",
    gender: "",
    contact_number: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <template #title> Sign up an account </template>

        <form @submit.prevent="submit">
            <div class="flex flex-col md:flex-row justify-between md:space-x-8">
                <div class="mt-4 flex-1">
                    <InputField
                        id="firstName"
                        v-model="form.firstName"
                        type="text"
                        label="First Name"
                        placeholder="First Name"
                        :error="form.errors.firstName"
                    />
                </div>
            </div>

            <div class="mt-4 flex-1">
                <InputField
                    id="middleName"
                    v-model="form.middleName"
                    type="text"
                    label="Middle Name (Optional)"
                    placeholder="Middle Name (Optional)"
                    :error="form.errors.middleName"
                />
            </div>

            <div class="mt-8 flex-1">
                <InputField
                    id="lastName"
                    v-model="form.lastName"
                    type="text"
                    label="Last Name"
                    placeholder="Last Name"
                    :error="form.errors.lastName"
                />
            </div>

            <div class="mt-8">
                <InputField
                    id="email"
                    v-model="form.email"
                    type="text"
                    label="Email Address"
                    placeholder="Email Address"
                    :error="form.errors.email"
                />
            </div>

            <div class="flex justify-between space-x-8">
                <div class="mt-6 flex-1">
                    <SelectInput
                        id="gender"
                        v-model="form.gender"
                        label="Gender"
                        :error="form.errors.gender"
                        :canSearch="false"
                    >
                        <option value="" selected hidden></option>

                        <option value="Male" :selected="form.gender === 'Male'">
                            Male
                        </option>
                        <option
                            value="Female"
                            :selected="form.gender === 'Female'"
                        >
                            Female
                        </option>
                    </SelectInput>
                </div>

                <div class="mt-6 flex-1">
                    <InputField
                        id="contact_number"
                        v-model="form.contact_number"
                        type="number"
                        label="Contact Number"
                        placeholder="Contact Number"
                        :error="form.errors.contact_number"
                    />
                </div>
            </div>

            <div class="flex justify-between space-x-8">
                <div class="mt-6 flex-1">
                    <InputField
                        id="password"
                        v-model="form.password"
                        type="password"
                        label="Password"
                        placeholder="Password"
                        :error="form.errors.password"
                    />
                </div>

                <div class="mt-6 flex-1">
                    <InputField
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        label="Confirm Password"
                        placeholder="Confirm Password"
                        :error="form.errors.password_confirmation"
                    />
                </div>
            </div>

            <div
                v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                class="mt-6"
            >
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox
                            id="terms"
                            v-model:checked="form.terms"
                            name="terms"
                        />

                        <div class="ml-2">
                            I agree to the
                            <a
                                target="_blank"
                                :href="route('terms.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Terms of Service</a
                            >
                            and
                            <a
                                target="_blank"
                                :href="route('policy.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Privacy Policy</a
                            >
                        </div>
                    </div>
                </InputLabel>
            </div>

            <div class="flex mt-6">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>

            <div class="mb-4 mt-6">
                <p class="font-light text-gray-500 text-sm md:text-md">
                    Already registered?
                    <Link
                        :href="route('login')"
                        class="font-medium text-blue-500 hover:text-blue-700 transition-all duration-200"
                        >Sign in</Link
                    >
                </p>
            </div>
        </form>
    </AuthenticationCard>
</template>
