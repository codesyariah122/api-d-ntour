<template>
    <div>
        <div class="flex justify-center place-content-center">
            <div class="mt-6">
                <button
                    @click.prevent="logout"
                    class="px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                >
                    Logout
                </button>
            </div>
        </div>
        <h1 class="capitalize">Dashboard {{ role }}</h1>
        <div
            class="bg-green-100 rounded-lg py-5 px-6 mb-3 text-base text-green-700 inline-flex items-center w-full"
            role="alert"
        >
            <svg
                aria-hidden="true"
                focusable="false"
                data-prefix="fas"
                data-icon="check-circle"
                class="w-4 h-4 mr-2 fill-current"
                role="img"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"
            >
                <path
                    fill="currentColor"
                    d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"
                ></path>
            </svg>
            Halo, welcome back {{ user.name }} with activation id
            {{ user.activation_id }}
        </div>
    </div>
</template>

<script>
import { getToken } from "../../helpers";

export default {
    data() {
        return {
            app_env: process.env.MIX_APP_ENV,
            server_url: process.env.MIX_APP_URL,
            api_url: process.env.MIX_API_URL,
            public_api: process.env.MIX_API_PUBLIC,
            server_url_public: process.env.MIX_APP_PUBLIC,
            token: getToken("token"),
            role: this.$route.params.role,
            token: getToken("token"),
            user: {},
            username: "",
            userId: null,
            googleId: null,
        };
    },

    mounted() {
        this.userData();
    },

    methods: {
        userData() {
            try {
                if (this.token.token) {
                    const endPoint = `${
                        this.app_env === "local"
                            ? this.api_url
                            : this.public_api
                    }/fitur/user-login`;
                    this.axios.defaults.headers.common.Authorization = `Bearer ${this.token.token}`;
                    this.axios
                        .get(endPoint)
                        .then(({ data }) => {
                            if (data.data.google_id) {
                                this.googleId = data.data.google_id;
                            }
                            this.user = data.data;
                            this.username = data.data.profiles[0].username;
                            this.userId = data.data.id;
                        })
                        .catch((err) => console.log(err.response));
                }
            } catch (err) {
                console.log(err.message);
            }
        },
        logout() {
            try {
                this.$swal({
                    title: `Are you exit as ${this.user?.name}?`,
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, logout!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const endPoint = `${this.api_url}/auth/logout`;
                        this.axios.defaults.headers.common.Authorization = `Bearer ${this.token?.token}`;
                        this.axios
                            .post(endPoint)
                            .then(({ data }) => {
                                if (data.success) {
                                    this.$swal(
                                        `Your exit from ${data?.data?.name} profile !`,
                                        data?.message,
                                        "success"
                                    );
                                    localStorage.removeItem("token");
                                    localStorage.removeItem("user-roles");
                                    this.$router.push("/");
                                }
                            })
                            .catch((err) => console.log(err.message));
                    }
                });
            } catch (err) {
                console.log(err.message);
            }
        },
    },
};
</script>
