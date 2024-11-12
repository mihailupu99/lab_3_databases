<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <Header />

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <slot></slot>
            <!-- Content will be injected here -->
        </main>
    </div>
</template>

<script>
import Header from "../Components/Header.vue";

export default {
    components: {
        Header,
    },
};
</script>

<style scoped>
/* You can add additional scoped styles here */
</style>
