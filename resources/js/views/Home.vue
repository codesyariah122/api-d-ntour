<template>
    <default-layout>
        <Hero />
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import Hero from "../components/Home/Hero";

export default {
    name: "Home",
    components: {
        DefaultLayout,
        Hero,
    },
};
</script>
