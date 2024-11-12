<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tasks</h1>

        <div class="flex justify-end mb-6">
            <Link
                href="/tasks/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
            >
                Create Task
            </Link>
        </div>

        <ul class="space-y-4">
            <li
                v-for="task in tasks"
                :key="task.id"
                class="bg-gray-100 p-4 rounded-lg shadow-md"
            >
                <inertia-link
                    :href="`/tasks/${task.id}`"
                    class="text-lg font-medium text-indigo-600 hover:underline"
                >
                    {{ task.title }}
                </inertia-link>
            </li>
        </ul>
    </div>
</template>

<script>
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout,

    props: {
        tasks: Array,
    },
};
</script>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
</script>
