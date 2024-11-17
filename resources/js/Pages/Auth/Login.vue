<script setup>
import { useForm } from "@inertiajs/vue3";
import { route } from "../../../../vendor/tightenco/ziggy/src/js";
import { Link } from "@inertiajs/vue3";

const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const submit = () => {
    form.post(route("login"), {
        onError: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="| Register"></Head>
    <div class="w-3/4 mx-auto">
        <h1 class="text-4xl pb-8">Login to your account</h1>

        <form @submit.prevent="submit">
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

            <div class="mb-6">
                <div
                    class="flex flex-row space-between justify-between items-center"
                >
                    <div class="flex gap-2 items-center">
                        <label for="remember">Remember me</label>
                        <input
                            id="remember"
                            type="checkbox"
                            v-mode="form.remember"
                        />
                    </div>
                    <p class="text-slate-600 mb-2">
                        Not yet a user?
                        <Link :href="route('register')" class="text-green-600"
                            >Register</Link
                        >
                    </p>
                </div>
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
                        form.processing ? "Processing..." : "Login"
                    }}</span>
                </button>
            </div>
        </form>
    </div>
</template>
