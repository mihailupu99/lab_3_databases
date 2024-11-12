<template>
    <header class="bg-cyan-600 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo or Brand Name -->
            <div class="text-lg font-bold">TODO applet</div>

            <!-- Navbar -->
            <nav>
                <ul class="flex space-x-6">
                    <li>
                        <Link href="/" class="hover:text-gray-300">Home</Link>
                    </li>
                    <li>
                        <Link href="/tasks/create" class="hover:text-gray-300"
                            >Add new task</Link
                        >
                    </li>
                    <li>
                        <Link href="/contact" class="hover:text-gray-300"
                            >Contact</Link
                        >
                    </li>
                </ul>
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
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
</script>
<style scoped>
/* Header-specific styles */
</style>
