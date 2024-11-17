<template>
    <div class="max-w-8xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tasks</h1>

        <div class="flex justify-end mb-6">
            <Link
                href="/tasks/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
            >
                Create Task
            </Link>
        </div>

        <div
            class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="task in tasks.data"
                :key="task.id"
                class="bg-gray-100 p-4 rounded-lg shadow-md"
            >
                <div class="flex flex-row justify-between pb-2">
                    <Link
                        :href="`/tasks/${task.id}/edit`"
                        class="text-blue-600 hover:bg-gray-300 py-1 px-2 rounded-xl flex items-center gap-1"
                        ><span class="material-symbols-outlined text-md">
                            edit
                        </span>
                        Edit
                    </Link>
                    <!-- Delete Button -->
                    <button
                        @click.prevent="confirmDelete(task.id)"
                        class="text-red-600 hover:bg-gray-300 py-1 px-2 rounded-xl flex items-end gap-1"
                    >
                        <span class="material-symbols-outlined text-md"
                            >delete</span
                        >
                        Delete
                    </button>
                </div>
                <h1 class="pb-2">
                    <Link
                        :href="`/tasks/${task.id}`"
                        class="text-2xl font-medium text-gray-700 hover:underline"
                    >
                        {{ task.title }}
                    </Link>
                </h1>
                <p>
                    <span class="text-sm font-medium text-gray-500">
                        Created At:
                    </span>
                    <span class="text-sm font-medium text-gray-700">{{
                        formatDate(task.created_at)
                    }}</span>
                </p>
                <!-- Display tags -->
                <p class="mt-2 text-sm text-gray-600">
                    <strong>Tags: </strong>
                    <span
                        class="bg-teal-600 text-white px-2 mr-1 rounded-xl"
                        v-for="(tag, index) in task.tags"
                        :key="tag.id"
                    >
                        {{ tag.name
                        }}<span v-if="index < task.tags.length - 1"></span>
                    </span>
                </p>
                <!-- Display category name -->
                <p class="mt-2 text-sm text-gray-600">
                    <strong>Category:</strong>
                    <span class="bg-blue-600 text-white px-2 ml-1 rounded-xl">
                        {{ task.category ? task.category.name : "No category" }}
                    </span>
                </p>
                <p class="text-base font-medium text-gray-700 pt-2">
                    {{ task.description }}
                </p>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-6 flex items-center justify-center gap-2">
            <Link
                v-for="(link, key) in tasks.links"
                :key="key"
                :href="link.url"
                v-html="link.label"
                class="px-4 py-2 border rounded-lg"
                :class="{
                    'bg-indigo-600 text-white': link.active,
                    'bg-white text-gray-700 hover:bg-gray-50': !link.active,
                    'opacity-50 cursor-not-allowed': !link.url,
                }"
            />
        </div>

        <!-- Optional: Show pagination info -->
        <div class="mt-4 text-center text-sm text-gray-600">
            Showing {{ tasks.from }} to {{ tasks.to }} of
            {{ tasks.total }} tasks
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tasks: Object, // Changed from Array to Object since paginated data is an object
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
        confirmDelete(id) {
            if (confirm("Are you sure you want to delete this task?")) {
                this.deleteTask(id);
            }
        },
        deleteTask(id) {
            this.$inertia.delete(`/tasks/${id}`, {
                onSuccess: () => alert("Task deleted successfully!"),
            });
        },
    },
};
</script>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
