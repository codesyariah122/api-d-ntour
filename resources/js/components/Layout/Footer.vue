<template>
    <footer class="mt-auto top-0 w-full text-center mb-6 hover:text-blue-700">
        <a :href="privacy"> &copy; D & N Tour Travel @ {{ year }} </a>
    </footer>
</template>

<script>
export default {
    data() {
        return {
            year: new Date().getFullYear(),
            privacy: "/privacy-policy",
        };
    },
};
</script>
