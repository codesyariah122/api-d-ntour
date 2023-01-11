<template>
    <div>
        <div
            v-if="loadingUser"
            class="flex justify-center items-center h-screen"
        >
            <div
                class="flex flex-col m-8 rounded shadow-md w-60 sm:w-80 animate-pulse h-96"
            >
                <div class="w-full h-6 rounded dark:bg-gray-700"></div>
                <div class="h-48 rounded-t dark:bg-gray-700"></div>
                <div class="flex-1 px-4 py-8 space-y-4 sm:p-8 dark:bg-gray-900">
                    <div
                        class="mt-6 mb-6 w-full h-6 rounded dark:bg-gray-700"
                    ></div>
                    <div class="w-3/4 h-6 rounded dark:bg-gray-700"></div>
                </div>
            </div>
        </div>

        <div
            v-else
            class="grid grid-cols-1 place-content-center place-items-center h-screen"
        >
            <div>
                <h1 class="text-green-600 text-2xl font-bold font-mono">
                    Hi, {{ user.name }} Your account it's {{ user.status }} !
                </h1>
            </div>
            <div>
                <img
                    :src="context.activate.second_vector"
                    alt=""
                    class="w-64"
                />
            </div>
            <div class="w-80 place-self-center">
                <blockquote
                    class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                >
                    please click the activate button below 👇
                </blockquote>
                <form @submit.prevent="activatedUser">
                    <input type="hidden" v-model="user.id" />
                    <button
                        class="px-6 py-4 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-80"
                    >
                        <div
                            v-if="loadingActivated"
                            class="flex justify-center items-center"
                        >
                            <div
                                class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full"
                                role="status"
                            >
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <span v-else> Activated </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import qs from "qs";

export default {
    data() {
        return {
            context: window?.context,
            activation_id: this.$route.params.token,
            api_url: process.env.MIX_API_URL,
            user: {},
            username: "",
            loadingUser: null,
            loadingActivated: null,
        };
    },

    mounted() {
        this.userInActiveData();
    },

    methods: {
        activatedUser() {
            // let params = new URLSearchParams();
            // params.append("id", this.user.id);
            try {
                this.loadingActivated = true;
                let data = qs.parse({
                    activation_id: this.activation_id,
                });
                const endPoint = `${this.api_url}/auth/activation/${this.user?.id}`;
                this.axios.defaults.headers.common["Content-Type"] =
                    "application/x-www-form-urlencoded;charset=utf-8";
                this.axios
                    .put(endPoint, data)
                    .then(({ data }) => {
                        if (data?.success) {
                            this.$swal({
                                position: "top-end",
                                icon: "success",
                                title: `${data?.message}👍`,
                                showConfirmButton: false,
                                timer: 2500,
                            });
                        } else {
                            if (data?.active) {
                                this.$swal({
                                    position: "top-end",
                                    icon: "success",
                                    title: `Halo, ${this.user?.name} activated successfully 👍`,
                                    showConfirmButton: false,
                                    timer: 2500,
                                });
                            }
                        }
                        setTimeout(() => {
                            this.loadingActivated = false;
                            this.$router.push(
                                `/auth/login?activation_id=${data?.data?.activation_id}`
                            );
                        }, 2500);
                    })
                    .catch((err) => console.log(err));
            } catch (err) {
                console.log(err.message);
            }
        },
        userInActiveData() {
            try {
                this.loadingUser = true;
                const endPoint = `${this.api_url}/auth/user-inactive/${this.activation_id}`;
                this.axios
                    .get(endPoint)
                    .then(({ data }) => {
                        if (data?.data) {
                            data?.data?.users.map((user) => (this.user = user));
                            data?.user_profile?.profiles?.map((profile) => {
                                this.username = profile.username;
                            });
                        }
                    })
                    .finally(() => {
                        setTimeout(() => {
                            this.loadingUser = false;
                        }, 1500);
                    })
                    .catch((err) => console.log(err));
            } catch (err) {
                console.log(err.message);
            }
        },
    },
};
</script>
