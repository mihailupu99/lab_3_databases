<template>
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Task</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Title -->
            <div>
                <label
                    for="title"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Title</label
                >
                <input
                    v-model="form.title"
                    type="text"
                    id="title"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter task title"
                    required
                />
            </div>

            <!-- Description -->
            <div>
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="4"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter task description"
                ></textarea>
            </div>

            <!-- Category -->
            <div>
                <label
                    for="category"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    id="category"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="" disabled>Select a category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Tags -->
            <div>
                <label
                    for="tags"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Tags</label
                >
                <select
                    v-model="form.tags"
                    id="tags"
                    multiple
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                        {{ tag.name }}
                    </option>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Hold <strong>Ctrl</strong> (or <strong>Cmd</strong> on Mac)
                    to select multiple tags.
                </p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="btn btn-primary bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    Save Task
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    layout: DefaultLayout,
    props: {
        categories: Array, // List of categories passed from the server
        tags: Array, // List of tags passed from the server
    },
    setup() {
        const form = useForm({
            title: "",
            description: "",
            category_id: null, // Category ID
            tags: [], // Selected tags
        });

        function submit() {
            form.post("/tasks");
        }

        return { form, submit };
    },
};
</script>

<style scoped>
.input {
    transition: all 0.3s ease-in-out;
}

.input:focus {
    outline: none;
}
</style>
