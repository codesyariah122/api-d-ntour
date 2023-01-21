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
    data() {
        return {
            app_env: process.env.MIX_APP_ENV,
            server_url: process.env.MIX_APP_URL,
            api_url: process.env.MIX_API_URL,
            public_api: process.env.MIX_API_PUBLIC,
            server_url_public: process.env.MIX_APP_PUBLIC,
            shelters: [],
        };
    },

    mounted() {
        this.getShelterData();
    },

    methods: {
        getShelterData() {
            const endPoint = `${
                this.app_env === "local" ? this.api_url : this.public_api
            }/shelter`;
            const config = {
                headers: {
                    Accept: "application/json",
                    "X-Header-DNTour": process.env.MIX_API_TOKEN,
                },
            };

            this.axios
                .get(endPoint, config)
                .then(({ data }) => {
                    console.log(data);
                })
                .catch((err) => console.log(err));
        },
    },
};
</script>
