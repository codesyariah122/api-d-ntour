<template>
    <div
        class="grid grid-cols-2 place-content-center place-items-center h-screen"
    >
        <div>
            <img :src="context.forgot.second_vector" alt="" class="w-full" />
        </div>
        <div>
            <h1 class="text-green-600 text-2xl font-bold font-mono">
                D & N Tour Travel - Reset Password Account
            </h1>
            <blockquote class="text-gray-400 font-serif mt-6 mb-6 capitalize">
                please fill new password here ! 👇
            </blockquote>

            <form @submit.prevent="updatePassword">
                <input type="hidden" v-model="form.token" />
                <input
                    type="password"
                    class="form-control block px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80"
                    id="exampleFormControlInput2"
                    placeholder="New Password"
                    v-model="form.password"
                />
                <div v-if="validation.password">
                    <small
                        v-for="(validate, idx) in validation.password"
                        :key="idx"
                        class="text-red-600 font-bold font-mono"
                    >
                        {{ validate }} <br />
                    </small>
                </div>
                <input
                    type="password"
                    class="form-control block px-4 py-2 mt-6 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80"
                    id="exampleFormControlInput2"
                    placeholder="New Password Confirmation"
                    v-model="form.password_confirmation"
                />
                <button
                    class="px-4 py-4 mt-6 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-80"
                >
                    <div
                        v-if="loadingReset"
                        class="flex justify-center items-center"
                    >
                        <div
                            class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full"
                            role="status"
                        >
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <span v-else> Update Password </span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            context: window?.context,
            apiToken: process.env.MIX_API_TOKEN,
            api_url: process.env.MIX_API_URL,
            token_reset: this.$route.params.token_reset,
            loadingReset: null,
            form: {},
            user: {},
            validation: [],
        };
    },

    mounted() {
        // console.log(this.token_reset);
        if (this.token_reset) this.form.token = this.token_reset;
    },

    methods: {
        updatePassword() {
            try {
                this.loadingReset = true;
                const endPoint = `${this.api_url}/reset`;
                const request = {
                    token: this.form.token,
                    password: this.form.password,
                    password_confirmation: this.form.password_confirmation,
                };

                const form = new FormData();
                form.append("token", this.form.token);
                form.append("password", this.form.password);
                form.append(
                    "password_confirmation",
                    this.form.password_confirmation
                );

                this.axios
                    .post(endPoint, form)
                    .then(({ data }) => {
                        if (data?.success) {
                            this.$swal({
                                position: "top-end",
                                icon: "success",
                                title: data?.message,
                                showConfirmButton: false,
                                timer: 2500,
                            });

                            setTimeout(() => {
                                this.loadingReset = false;
                                localStorage.removeItem("reset-password");
                                this.$router.replace(
                                    "/auth/login?q=form-reset"
                                );
                            }, 2500);
                        } else {
                            if (data?.error_token) {
                                this.$swal({
                                    icon: "error",
                                    title: "Oops...",
                                    text: data?.message,
                                });
                                setTimeout(() => {
                                    this.loadingReset = false;
                                    localStorage.getItem("reset-password")
                                        ? localStorage.removeItem(
                                              "reset-password"
                                          )
                                        : "";
                                    this.$router.replace(
                                        "/auth/login?q=form-reset"
                                    );
                                }, 1500);
                            }
                        }
                    })
                    .catch((err) => {
                        if (err.response.data) {
                            this.$swal({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                            setTimeout(() => {
                                this.loadingReset = false;
                                this.validation = err.response.data;
                            }, 1500);
                        }
                    });
            } catch (err) {
                console.log(err.message);
            }
        },
    },
};
</script>
