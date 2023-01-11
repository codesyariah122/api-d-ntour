<template>
    <div
        class="grid grid-cols-1 place-content-center place-items-center h-screen"
    >
        <div>
            <h1 class="text-green-600 text-2xl font-bold font-mono">
                Hi, {{ user.name }} Your account has been activate !
            </h1>
        </div>
        <div>
            <img :src="context.activate.first_vector" alt="" class="w-64" />
        </div>
        <div>
            <button
                @click.prevent="profile(google_id)"
                class="px-6 py-4 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
            >
                Go To Profile
            </button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            context: window?.context,
            token: this.$route.query.access_token,
            server_url: process.env.MIX_SERVER_URL,
            user: {},
            google_id: "",
        };
    },

    mounted() {
        this.checkUserLogin();
    },

    methods: {
        profile(id) {
            this.$router.push(`/profile/${id}`);
        },
        checkUserLogin() {
            try {
                if (this.token) {
                    const endPoint = `${this.server_url}/api/user`;
                    this.axios.defaults.headers.common.Authorization = `Bearer ${this.token}`;
                    this.axios.defaults.headers.common["Content-Type"] =
                        "application/json";
                    this.axios.defaults.headers.common["Accept"] =
                        "application/json";
                    this.axios
                        .get(endPoint)
                        .then(({ data }) => {
                            if (data?.data?.google_id) {
                                localStorage.setItem(
                                    "token",
                                    JSON.stringify({ token: this.token })
                                );
                                localStorage.setItem(
                                    "user-roles",
                                    JSON.stringify({
                                        name: data?.data?.name,
                                        email: data?.data?.email,
                                        roles: data?.data?.roles,
                                        google_id: data?.data?.google_id,
                                    })
                                );
                                this.user = data?.data;
                                this.google_id = data?.data?.google_id;
                            }
                        })
                        .catch((err) => console.log(err.message));
                }
            } catch (err) {
                console.log(err.message);
            }
        },
    },
};
</script>
