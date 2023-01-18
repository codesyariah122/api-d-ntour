<template>
    <default-layout>
        <section class="h-screen mt-6">
            <div class="container px-6 py-12 h-full">
                <div
                    class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800"
                >
                    <div
                        class="md:w-8/12 lg:w-6/12 md:mb-12 md:mt-0 mt-12 sm:mb-16"
                    >
                        <img
                            :src="context.hero.image"
                            class="w-full rounded-md shadow-md drop-shadow-md"
                            alt="Phone image"
                        />
                    </div>
                    <div class="md:w-8/12 lg:w-5/12 lg:ml-20">
                        <center>
                            <img
                                :src="context.hero.logo"
                                class="mb-6 w-48 rounded-md"
                                alt="brand-logo"
                            />
                        </center>
                        <form @submit.prevent="login">
                            <!-- Email input -->
                            <div class="mb-6">
                                <input
                                    type="text"
                                    class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                    placeholder="Email address"
                                    v-model="form.email"
                                />
                                <div v-if="validation.email">
                                    <small
                                        class="text-red-600 font-bold font-mono"
                                    >
                                        {{ validation.email[0] }}
                                    </small>
                                </div>
                            </div>

                            <!-- Password input -->
                            <div class="mb-6">
                                <div
                                    class="flex justify-center border border-solid bg-white bg-clip-padding border-gray-300 rounded transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                >
                                    <div class="grow h-8 w-full mt-2 md:ml-4">
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control text-xl font-normal text-gray-700 border-none focus:outline-none focus:border-0 focus:ring-0 focus:border-transparent w-full"
                                            placeholder="Password"
                                            v-model="form.password"
                                        />
                                    </div>
                                    <div class="grow-0 h-10 mt-3 md:mr-4">
                                        <span
                                            @click="showPassword"
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
                                <div v-if="validation.password">
                                    <small
                                        class="mt-2 text-red-600 font-bold font-mono"
                                    >
                                        {{ validation.password[0] }}
                                    </small>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-6">
                                <div class="form-group form-check">
                                    <input
                                        v-model="remember_me"
                                        type="checkbox"
                                        class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                        id="exampleCheck3"
                                        checked
                                    />
                                    <label
                                        class="form-check-label inline-block text-gray-800"
                                        for="exampleCheck2"
                                        >Remember me</label
                                    >
                                </div>
                                <a
                                    :href="`/auth/forgot`"
                                    class="text-blue-600 hover:text-blue-700 focus:text-blue-700 active:text-blue-800 duration-200 transition ease-in-out"
                                    >Forgot password?</a
                                >
                            </div>

                            <!-- Submit button -->
                            <button
                                type="submit"
                                class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
                                data-mdb-ripple="true"
                                data-mdb-ripple-color="light"
                            >
                                <div class="flex justify-center items-center">
                                    <div
                                        v-if="loginLoading"
                                        class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full"
                                        role="status"
                                    >
                                        <span class="visually-hidden"
                                            >Loading...</span
                                        >
                                    </div>
                                    <div v-else class="flex-none">Sign in</div>
                                </div>
                            </button>

                            <div
                                class="flex items-center my-4 before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5"
                            >
                                <p class="text-center font-semibold mx-4 mb-0">
                                    OR
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </default-layout>
</template>

<script>
import DefaultLayout from "../../layouts/DefaultLayout.vue";
import { checkLogin, getRoles } from "../../helpers.js";

export default {
    name: "Home",
    components: {
        DefaultLayout,
    },
    data() {
        return {
            context: window?.context,
            app_env: process.env.MIX_APP_ENV,
            public_api: process.env.MIX_API_PUBLIC,
            server_url_public: process.env.MIX_APP_PUBLIC,
            api_url: process.env.MIX_API_URL,
            server_url: process.env.MIX_APP_URL,
            form: {},
            remember_me: false,
            loginLoading: null,
            userData: {},
            name: "",
            userName: "",
            googleId: "",
            roles: "",
            apiToken: process.env.MIX_API_TOKEN,
            showing: false,
            validation: [],
        };
    },

    mounted() {
        this.isLogin();
    },

    methods: {
        showPassword() {
            const password = document.querySelector("#password");
            if (password.type === "password") {
                password.type = "text";
                this.showing = true;
            } else {
                password.type = "password";
                this.showing = false;
            }
        },
        isLogin() {
            try {
                const token = localStorage.getItem("token")
                    ? JSON.parse(localStorage.getItem("token"))
                    : null;
                const endPoint = `${
                    this.app_env === "local" ? this.api_url : this.public_api
                }/fitur/user-login`;
                this.axios.defaults.headers.common.Authorization = `Bearer ${token.token}`;
                this.axios
                    .get(endPoint)
                    .then(({ data }) => {
                        const roles = getRoles(data.data.roles);
                        if (data?.data?.is_login || token) {
                            this.isLoginAlert(
                                `You are is login as a ${roles} Roles`,
                                roles
                            );
                            setTimeout(() => {
                                if (roles === "customer") {
                                    this.$router.push(
                                        `/profile/${data?.data?.profiles[0]?.username}`
                                    );
                                } else {
                                    this.$router.push(
                                        `/dashboard/${data?.data?.profiles[0]?.username}`
                                    );
                                }
                            }, 2500);
                        }
                    })
                    .catch((err) => {
                        console.log(err.response);
                    });
            } catch (err) {
                console.log(err);
            }
        },
        isLoginAlert(msg, data) {
            this.$swal({
                icon: "warning",
                title: `${msg}`,
                text: `Welcome ${data}`,
            });
        },
        isActivateAlert(msg, link) {
            this.$swal({
                icon: "error",
                title: `Oops ${msg}`,
                text: "Please activate user now !!",
                footer: `<a href="${link}">Activate user from this link?</a>`,
            });
            setTimeout(() => {
                this.loginLoading = false;
            }, 1500);
        },
        isErrorAlert(msg, empty) {
            this.$swal({
                icon: "error",
                title: `Oops ${msg}`,
                text: `${
                    empty
                        ? "Form input is empty!"
                        : `Error login failed / ${msg} ! `
                }`,
            });
            setTimeout(() => {
                this.loginLoading = false;
            }, 1500);
        },
        isNotFoundAlert(msg) {
            this.$swal({
                icon: "warning",
                title: `Oops ${msg}`,
                text: "Try to register / signup",
            });
            setTimeout(() => {
                this.loginLoading = false;
            }, 1500);
        },
        isSuccessAlert(msg, data, token) {
            this.$swal({
                position: "top-end",
                icon: "success",
                title: `Halo, ${data.name} selamat datang`,
                showConfirmButton: false,
                timer: 1500,
            });

            const roles = getRoles(data.roles);

            setTimeout(() => {
                this.loginLoading = false;
                localStorage.setItem("token", JSON.stringify({ token: token }));
                localStorage.setItem(
                    "user-roles",
                    JSON.stringify({
                        username: this.username,
                        roles: roles,
                    })
                );
                roles === "admin"
                    ? this.$router.push(`/dashboard/${roles}`)
                    : this.$router.push(
                          `/profile/${
                              this.googleId ? this.googleId : this.username
                          }`
                      );
            }, 1500);
        },
        login() {
            try {
                this.loginLoading = true;
                const endPoint = `${
                    this.app_env === "local" ? this.api_url : this.public_api
                }/auth/login`;
                console.log(endPoint);
                this.axios
                    .post(endPoint, {
                        email: this.form?.email,
                        password: this.form?.password,
                        remember_me: this.remember_me,
                    })
                    .then(({ data }) => {
                        console.log(data);
                        if (data.not_found) {
                            this.isNotFoundAlert(data.message);
                        } else {
                            if (data.in_active) {
                                this.isActivateAlert(data.message, data.link);
                            } else {
                                if (!data.success) {
                                    if (data.status_reset) {
                                        this.isErrorAlert(data.message, false);
                                        setTimeout(() => {
                                            this.$router.push(
                                                `/auth/reset/${data.data.token}`
                                            );
                                        }, 1500);
                                    }
                                    this.isErrorAlert(data.message, false);
                                } else {
                                    this.isSuccessAlert(
                                        data.message,
                                        data.data[0],
                                        data.data[0].logins[0].user_token_login
                                    );
                                    data.data?.map((user) => {
                                        this.googleId = user?.google_id;
                                        user?.profiles.map((profile) => {
                                            this.username = profile.username;
                                        });
                                    });
                                    this.userData = data.data[0];
                                }
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
                                this.loginLoading = false;
                                this.validation = err.response.data;
                            }, 1500);
                        }
                    });
            } catch (err) {
                console.log(err.message);
            }
        },
    },
    computed: {},
};
</script>
