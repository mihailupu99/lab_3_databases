<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ task.title }}</h1>
            <Link
                :href="`/tasks/${task.id}/edit`"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
            >
                Edit Task
            </Link>
        </div>

        <p class="text-gray-600 mb-4">
            <strong>Created At:</strong>
            {{ formatDate(task.created_at) }}
        </p>
        <p class="text-gray-600 mb-4">
            <strong>Category:</strong>
            <span class="bg-blue-600 text-white text-sm px-2 ml-1 rounded-xl">
                <span class="bg-blue-600 text-white px-2 rounded-xl">
                    {{ task.category ? task.category.name : "No category" }}
                </span>
            </span>
        </p>
        <p class="text-gray-600 mb-4">
            <strong>Tags:</strong>
            <span
                v-for="(tag, index) in task.tags"
                :key="tag.id"
                class="bg-teal-600 text-white text-sm px-2 ml-1 rounded-xl"
            >
                {{ tag.name }}<span v-if="index < task.tags.length - 1"></span>
            </span>
        </p>
        <p class="text-gray-700">{{ task.description }}</p>
    </div>
</template>

<script>
export default {
    props: {
        task: Object,
    },
    methods: {
        formatDate(date) {
            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
            };
            return new Date(date).toLocaleString("en-GB", options);
        },
    },
};
</script>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
