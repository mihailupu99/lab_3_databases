<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, onUnmounted } from "vue";
import { route } from "../../../../vendor/tightenco/ziggy/src/js";
import { Link } from "@inertiajs/vue3";

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
});

const success = ref(false);

const submit = () => {
    form.post(route("register"), {
        onSuccess: () => {
            success.value = true; // Show success message
        },
        onError: () => form.reset("password", "password_confirmation"),
    });
};

onUnmounted(() => {
    success.value = false;
});
</script>

<template>
    <Head title="| Register"></Head>
    <div class="w-3/4 mx-auto">
        <h1 class="text-4xl pb-8">Register a new account</h1>
        <!-- Name -->
        <!-- Success Message -->
        <div
            v-if="success"
            class="p-6 bg-green-100 border border-green-500 text-green-800 rounded-lg"
        >
            <h2 class="text-2xl font-bold">Registration Successful!</h2>
            <p class="mt-2">
                Thank you for registering. You are now logged in.
            </p>
        </div>

        <form v-else @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2"
                    >Name</label
                >
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                    placeholder="Enter your name"
                />
                <small class="text-red-600">{{ form.errors.name }}</small>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2"
                    >Email</label
                >
                <input
                    type="email"
                    id="email"
                    v-model="form.email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                    placeholder="Enter your email"
                />
                <small class="text-red-600">{{ form.errors.email }}</small>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label
                    for="password"
                    class="block text-gray-700 font-medium mb-2"
                    >Password</label
                >
                <input
                    type="password"
                    id="password"
                    v-model="form.password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                    placeholder="Create a password"
                />
                <small class="text-red-600">{{ form.errors.password }}</small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label
                    for="confirm-password"
                    class="block text-gray-700 font-medium mb-2"
                    >Confirm Password</label
                >
                <input
                    type="password"
                    id="confirm-password"
                    v-model="form.password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                    placeholder="Re-enter your password"
                />
            </div>
            <div class="mb-6">
                <p class="text-slate-600 mb-2">
                    Already a user?
                    <Link :href="route('login')" class="text-green-600"
                        >Login</Link
                    >
                </p>
                <!-- Submit Button -->
                <button
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300 flex items-center justify-center"
                    :disabled="form.processing"
                >
                    <svg
                        v-if="form.processing"
                        class="animate-spin h-5 w-5 mr-2 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v8H4z"
                        ></path>
                    </svg>
                    <span>{{
                        form.processing ? "Processing..." : "Register"
                    }}</span>
                </button>
            </div>
        </form>
    </div>
</template>
