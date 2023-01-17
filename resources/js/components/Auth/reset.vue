<template>
    <div>
        <div
            v-if="loadingCheck"
            class="container mx-auto bg-black dark:bg-black max-w-screen h-screen"
        >
            <div class="grid grid-cols-1">
                <div class="place-self-center">
                    <img :src="context.gif" alt="" />
                </div>
            </div>
        </div>
        <div v-else class="container mx-auto">
            <div class="md:hidden block">
                <div
                    class="grid grid-cols-1 place-content-center place-items-center h-screen"
                >
                    <div>
                        <img
                            :src="context.forgot.second_vector"
                            alt=""
                            class="w-80"
                        />
                    </div>
                    <div class="sm:col-span-1 w-80">
                        <h1 class="text-green-600 text-2xl font-bold font-mono">
                            D & N Tour Travel - Reset Password Account
                        </h1>
                        <small class="text-pink-500 mb-2">
                            Reset password for {{ data_reset.email }}
                        </small>
                        <blockquote
                            class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                        >
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
                                    v-for="(
                                        validate, idx
                                    ) in validation.password"
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
                                        <span class="visually-hidden"
                                            >Loading...</span
                                        >
                                    </div>
                                </div>
                                <span v-else> Update Password </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="hidden md:block">
                <div
                    class="grid grid-cols-2 place-content-center place-items-center h-screen"
                >
                    <div>
                        <img
                            :src="context.forgot.second_vector"
                            alt=""
                            class="w-full"
                        />
                    </div>
                    <div>
                        <h1 class="text-green-600 text-2xl font-bold font-mono">
                            D & N Tour Travel - Reset Password Account
                        </h1>
                        <small class="text-pink-500 mb-2">
                            Reset password for {{ data_reset.email }}
                        </small>
                        <blockquote
                            class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                        >
                            please fill new password here ! 👇
                        </blockquote>

                        <form @submit.prevent="updatePassword">
                            <input type="hidden" v-model="form.token" />
                            <div
                                class="flex justify-center border border-solid bg-white bg-clip-padding border-gray-300 rounded transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80 mb-6"
                            >
                                <div class="grow h-8 mt-2 md:ml-4">
                                    <input
                                        id="password1"
                                        type="password"
                                        class="form-control text-xl font-normal text-gray-700 border-none focus:outline-none focus:border-0 focus:ring-0 focus:border-transparent"
                                        placeholder="New Password"
                                        v-model="form.password"
                                    />
                                </div>
                                <div class="grow-0 h-10 mt-3 md:mr-4">
                                    <span
                                        @click="showPassword('#password1')"
                                        class="cursor-pointer"
                                    >
                                        <font-awesome-icon
                                            v-if="!showing"
                                            icon="fa-eye fa-lg"
                                        />

                                        <font-awesome-icon
                                            v-else
                                            icon="fa-eye-slash fa-lg"
                                        />
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="validation.password"
                                class="grid grid-cols-1 transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-full mb-6"
                            >
                                <small
                                    v-for="(
                                        validate, idx
                                    ) in validation.password"
                                    :key="idx"
                                    class="place-self-center text-red-600 font-bold font-mono"
                                >
                                    {{ validate }} <br />
                                </small>
                            </div>

                            <div
                                class="flex justify-center border border-solid bg-white bg-clip-padding border-gray-300 rounded transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80 mb-6"
                            >
                                <div class="grow h-8 mt-2 md:ml-4">
                                    <input
                                        id="password2"
                                        type="password"
                                        class="form-control text-xl font-normal text-gray-700 border-none focus:outline-none focus:border-0 focus:ring-0 focus:border-transparent"
                                        placeholder="New Password Confirmation"
                                        v-model="form.password_confirmation"
                                    />
                                </div>
                                <div class="grow-0 h-10 mt-3 md:mr-4">
                                    <span
                                        @click="showPassword('#password2')"
                                        class="cursor-pointer"
                                    >
                                        <font-awesome-icon
                                            v-if="!showingConfirm"
                                            icon="fa-eye fa-lg"
                                        />

                                        <font-awesome-icon
                                            v-else
                                            icon="fa-eye-slash fa-lg"
                                        />
                                    </span>
                                </div>
                            </div>

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
                                        <span class="visually-hidden"
                                            >Loading...</span
                                        >
                                    </div>
                                </div>
                                <span v-else> Update Password </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
            loadingCheck: null,
            form: {},
            user: {},
            validation: [],
            data_reset: {},
            showing: false,
            showingConfirm: false,
        };
    },

    mounted() {
        // console.log(this.token_reset);
        this.checkTokenResetPassword();
        if (this.token_reset) this.form.token = this.token_reset;
    },

    methods: {
        showPassword(id) {
            const password = document.querySelector(id);
            if (id === "#password1") {
                if (password.type === "password") {
                    password.type = "text";
                    this.showing = true;
                } else {
                    password.type = "password";
                    this.showing = false;
                }
            } else {
                if (password.type === "password") {
                    password.type = "text";
                    this.showingConfirm = true;
                } else {
                    password.type = "password";
                    this.showingConfirm = false;
                }
            }
        },
        checkTokenResetPassword() {
            try {
                this.loadingCheck = true;
                const endPoint = `${this.api_url}/check-reset-token/${this.token_reset}`;
                this.axios
                    .get(endPoint)
                    .then(({ data }) => {
                        // console.log(data);
                        if (!data?.valid) {
                            this.$swal(
                                "Forbidden access",
                                "That thing is still around?",
                                "danger"
                            );
                            this.$router.replace("/");
                        }
                        this.data_reset = data.token;
                    })
                    .finally(() => {
                        setTimeout(() => {
                            this.loadingCheck = false;
                        }, 1500);
                    })
                    .catch((err) => console.log(err.response));
            } catch (err) {
                console.log(err.message);
            }
        },
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
