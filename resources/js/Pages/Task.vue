<template>
    <DefaultLayout>
        <h1 class="text-4xl font-bold text-center text-gray-800">
            Add new task
        </h1>
        <p class="mt-4 text-lg text-gray-600 text-center">
            Complete the form bellow to add a new task
        </p>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout, // Apply the same layout as Home.vue
};
</script>

<style scoped>
/* Page-specific styles for About */
</style>
