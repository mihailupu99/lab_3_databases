<template>
    <header class="bg-gray-200 text-gray-700 font-medium py-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo or Brand Name -->
            <div class="text-lg font-bold">
                <Link :href="route('home')">TODO applet</Link>
            </div>

            <!-- Navbar -->
            <nav>
                <div class="flex space-x-6 items-center">
                    <Link
                        :href="route('home')"
                        class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                        >Home</Link
                    >
                    <div class="flex gap-4" v-if="$page.props.auth.user">
                        <Link
                            href="/tasks/create"
                            class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                        >
                            New task</Link
                        >
                        <Link
                            href="/tasks"
                            class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                        >
                            Current Tasks</Link
                        >
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            type="button"
                            class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                            >Logout</Link
                        >
                    </div>
                    <div class="flex gap-4" v-else>
                        <Link
                            :href="route('register')"
                            class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                            >Register</Link
                        >
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                            >Login</Link
                        >
                    </div>

                    <Link
                        href="/contact"
                        class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
                        >Contact</Link
                    >
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    name: "Header",
};
</script>

<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
</script>
