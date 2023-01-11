<template>
    <default-layout>
        <section class="h-screen mt-6">
            <div class="container px-6 py-12 h-full">
                <div
                    class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800"
                >
                    <div class="md:w-8/12 lg:w-6/12 mb-12 md:mb-12">
                        <img
                            :src="context.hero.image"
                            class="w-full"
                            alt="Phone image"
                        />
                    </div>
                    <div class="md:w-8/12 lg:w-5/12 lg:ml-20">
                        <center>
                            <img
                                :src="context.hero.logo"
                                class="mb-6 w-48"
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
                            </div>

                            <!-- Password input -->
                            <div class="mb-6">
                                <input
                                    type="password"
                                    class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                    placeholder="Password"
                                    v-model="form.password"
                                />
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
                                    href="#!"
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
            api_url: process.env.MIX_API_URL,
            form: {},
            remember_me: false,
            loginLoading: null,
            userData: {},
            name: "",
            userName: "",
            roles: "",
        };
    },

    mounted() {
        this.isLogin();
    },

    methods: {
        isLogin() {
            const roles = localStorage.getItem("user-roles")
                ? JSON.parse(localStorage.getItem("user-roles"))
                : null;
            const token = localStorage.getItem("token")
                ? JSON.parse(localStorage.getItem("token"))
                : null;
            if (token) {
                this.isLoginAlert(
                    `You are is login as a ${roles.roles} Roles`,
                    roles.roles
                );
                setTimeout(() => {
                    this.$router.push(`/dashboard/${roles?.roles}`);
                }, 2500);
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
                    : this.$router.push(`/profile/${this.username}`);
            }, 1500);
        },
        login() {
            try {
                this.loginLoading = true;
                const isEmpty = Object.values(this.form).every((val) => {
                    if (val === null || val === "") {
                        return true;
                    }
                    return false;
                });

                if (isEmpty) {
                    this.isErrorAlert("check input form login !!", isEmpty);
                } else {
                    const endPoint = `${this.api_url}/auth/login`;
                    this.axios
                        .post(endPoint, {
                            email: this.form?.email,
                            password: this.form?.password,
                            remember_me: this.remember_me,
                        })
                        .then(({ data }) => {
                            if (data.not_found) {
                                this.isNotFoundAlert(data.message);
                            } else {
                                if (data.in_active) {
                                    this.isActivateAlert(
                                        data.message,
                                        data.link
                                    );
                                } else {
                                    if (!data.success) {
                                        this.isErrorAlert(data.message, false);
                                    } else {
                                        this.isSuccessAlert(
                                            data.message,
                                            data.data[0],
                                            data.token
                                        );
                                        data.data?.map((u) => {
                                            u?.profiles.map((profile) => {
                                                this.username =
                                                    profile.username;
                                            });
                                        });
                                        this.userData = data.data[0];
                                    }
                                }
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            } catch (err) {
                console.log(err.message);
            }
        },
    },
    computed: {},
};
</script>
