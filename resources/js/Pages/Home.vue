<template>
    <DefaultLayout>
        <div
            class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-center"
        >
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome to our TODO applet
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Organize your tasks efficiently and boost your productivity.
            </p>

            <div
                class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
            >
                <!-- Card 1 -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        Create Tasks
                    </h2>
                    <p class="text-gray-600">
                        Easily create new tasks to keep track of your daily
                        to-dos.
                    </p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        Organize by Category
                    </h2>
                    <p class="text-gray-600">
                        Categorize your tasks to keep your workspace organized.
                    </p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        Tag Your Tasks
                    </h2>
                    <p class="text-gray-600">
                        Use tags to highlight important tasks and prioritize
                        your work.
                    </p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        Track Progress
                    </h2>
                    <p class="text-gray-600">
                        Monitor your progress and complete your tasks
                        efficiently.
                    </p>
                </div>
            </div>

            <div class="mt-10">
                <a
                    href="/tasks/create"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700"
                >
                    Get Started
                </a>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup></script>
