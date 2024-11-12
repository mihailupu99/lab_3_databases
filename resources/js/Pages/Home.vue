<template>
    <DefaultLayout>
        <h1 class="text-4xl font-bold text-center text-gray-800">
            Welcome to our TODO applet
        </h1>
        <p class="mt-4 text-lg text-gray-600 text-center">
            Take a look at our current todo# tasks bellow.
        </p>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../Layouts/DefaultLayout.vue";

export default {
    layout: DefaultLayout, // Use the DefaultLayout for this page
};
</script>

<style scoped>
/* You can add page-specific styles here */
</style>
