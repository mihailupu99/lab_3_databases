<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Task</h1>

        <form @submit.prevent="submit">
            <!-- Your existing form fields -->

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium"
                    >Title</label
                >
                <input
                    v-model="form.title"
                    type="text"
                    id="title"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="4"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ></textarea>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    id="category"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">No category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-medium"
                    >Tags</label
                >
                <div class="flex flex-wrap gap-2 mt-2">
                    <span
                        v-for="tag in allTags"
                        :key="tag.id"
                        class="px-2 py-1 border rounded-lg cursor-pointer"
                        :class="{
                            'bg-teal-600 text-white': form.tags.includes(
                                tag.id
                            ),
                            'bg-gray-200 text-gray-800': !form.tags.includes(
                                tag.id
                            ),
                        }"
                        @click="toggleTag(tag.id)"
                    >
                        {{ tag.name }}
                    </span>
                </div>
            </div>
            <!-- Add error messages -->
            <div v-if="form.errors.title" class="text-red-500 mt-1">
                {{ form.errors.title }}
            </div>

            <!-- Add loading state -->
            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 disabled:opacity-50"
                :disabled="form.processing"
            >
                {{ form.processing ? "Saving..." : "Save Changes" }}
            </button>
        </form>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
    props: {
        task: Object,
        categories: Array,
        allTags: Array, // Add this prop
    },
    setup(props) {
        const form = useForm({
            title: props.task.title,
            description: props.task.description,
            category_id: props.task.category_id || "",
            tags: props.task.tags.map((tag) => tag.id) || [],
        });

        const toggleTag = (tagId) => {
            const index = form.tags.indexOf(tagId);
            if (index > -1) {
                form.tags.splice(index, 1);
            } else {
                form.tags.push(tagId);
            }
        };

        const submit = () => {
            form.put(route("tasks.update", props.task.id), {
                onSuccess: () => {
                    // Optional: Add success handling
                },
                preserveScroll: true,
            });
        };

        return { form, toggleTag, submit };
    },
};
</script>
