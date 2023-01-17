<template>
    <div class="container mx-auto">
        <div class="md:hidden block">
            <div
                class="grid grid-cols-1 place-content-center place-items-center h-screen"
            >
                <div>
                    <img
                        :src="context.forgot.first_vector"
                        alt=""
                        class="w-80"
                    />
                </div>
                <div v-if="token_reset" class="sm:col-span-1 w-80">
                    <blockquote
                        class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                    >
                        D & N Tour Travel - Reset Password
                    </blockquote>
                    <div
                        class="bg-green-100 rounded-lg py-5 px-6 mb-4 text-base text-green-700"
                        role="alert"
                    >
                        Your password reset has been send to your email <br />
                        check your inbox
                        <a
                            @click="checkEmail(emailDomain(email_reset))"
                            class="hover:text-blue-700 text-blue-600 cursor-pointer"
                            >{{ email_reset }}</a
                        >
                    </div>
                </div>
                <div v-else class="sm:col-span-1 w-80">
                    <h1 class="text-green-600 text-2xl font-bold font-mono">
                        D & N Tour Travel - Reset Password Account
                    </h1>
                    <blockquote
                        class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                    >
                        please fill your email first for reset your password 👇
                    </blockquote>
                    <form @submit.prevent="resetPassword">
                        <input
                            type="text"
                            class="form-control block px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80"
                            id="exampleFormControlInput2"
                            placeholder="Email address"
                            v-model="user.email"
                        />
                        <div v-if="validation.email">
                            <small class="text-red-600 font-bold font-mono">
                                {{ validation.email[0] }}
                            </small>
                        </div>
                        <button
                            class="px-4 py-4 mt-6 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-80"
                        >
                            <div
                                v-if="loadingForgot"
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
                            <span v-else> Reset Password </span>
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
                        :src="context.forgot.first_vector"
                        alt=""
                        class="md:w-full max-w-screen-sm"
                    />
                </div>
                <div v-if="token_reset">
                    <blockquote
                        class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                    >
                        D & N Tour Travel - Reset Password
                    </blockquote>
                    <div
                        class="bg-green-100 rounded-lg py-5 px-6 mb-4 text-base text-green-700"
                        role="alert"
                    >
                        Your password reset has been send to your email <br />
                        check your inbox
                        <a
                            @click="checkEmail(emailDomain(email_reset))"
                            class="hover:text-blue-700 text-blue-600 cursor-pointer"
                            >{{ email_reset }}</a
                        >
                    </div>
                </div>
                <div v-else class="sm:col-span-12 md:col-span-1">
                    <h1 class="text-green-600 text-2xl font-bold font-mono">
                        D & N Tour Travel - Reset Password Account
                    </h1>
                    <blockquote
                        class="text-gray-400 font-serif mt-6 mb-6 capitalize"
                    >
                        please fill your email first for reset your password 👇
                    </blockquote>
                    <form @submit.prevent="resetPassword">
                        <input
                            type="text"
                            class="form-control block px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none w-80"
                            id="exampleFormControlInput2"
                            placeholder="Email address"
                            v-model="user.email"
                        />
                        <div v-if="validation.email">
                            <small class="text-red-600 font-bold font-mono">
                                {{ validation.email[0] }}
                            </small>
                        </div>
                        <button
                            class="px-4 py-4 mt-6 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-80"
                        >
                            <div
                                v-if="loadingForgot"
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
                            <span v-else> Reset Password </span>
                        </button>
                    </form>
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
            loadingForgot: null,
            user: {},
            username: "",
            tokenReset: null,
            emailReset: null,
            validation: [],
        };
    },

    mounted() {},

    methods: {
        emailDomain(email) {
            const domain = email.split("@");
            return `https://${domain[1]}`;
        },

        checkEmail(link) {
            window.location.replace(link);
        },

        resetPassword() {
            try {
                this.validation = [];
                this.loadingForgot = true;
                const endPoint = `${this.api_url}/forgot/${this.apiToken}`;
                const form = new FormData();
                form.append("email", this.user?.email);
                this.axios
                    .post(endPoint, form)
                    .then(({ data }) => {
                        if (data?.success) {
                            this.checkPasswordResetData(data?.data?.email);
                            this.$swal(
                                `Halo, ${data?.data?.name} password reset was send to your email ${data?.data?.email}`,
                                data?.message,
                                "success"
                            );
                            data?.data?.profiles?.map((profile) => {
                                this.username = profile?.username;
                            });
                            setTimeout(() => {
                                localStorage.setItem(
                                    "reset-password",
                                    JSON.stringify({
                                        username: this.username,
                                        email: data?.data?.email,
                                        token_reset: data?.token_reset,
                                    })
                                );
                                this.loadingForgot = false;
                            }, 2500);
                        } else {
                            this.$swal({
                                icon: "error",
                                title: "Oops...",
                                text: data?.message,
                            });
                            setTimeout(() => {
                                this.user.email = "";
                                this.loadingForgot = false;
                            }, 1500);
                        }
                    })
                    .catch((err) => {
                        if (err?.response?.data) {
                            this.$swal({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                            setTimeout(() => {
                                this.validation = err?.response?.data;
                                this.loadingForgot = false;
                            }, 1500);
                        }
                    });
            } catch (err) {
                console.log(err.message);
            }
        },

        checkPasswordResetData(email) {
            const endPoint = `${this.api_url}/password-reset-data/${email}`;
            try {
                this.axios
                    .get(endPoint)
                    .then(({ data }) => {
                        if (data?.reset) {
                            this.tokenReset = data?.data?.token;
                            this.emailReset = data?.data?.email;
                        }
                    })
                    .catch((err) => console.log(err));
            } catch (err) {
                console.log(err.message);
            }
        },
    },

    computed: {
        email_reset() {
            if (this.tokenReset) {
                return this.emailReset;
            } else {
                const dataReset = localStorage.getItem("reset-password")
                    ? JSON.parse(localStorage.getItem("reset-password"))
                    : null;
                return dataReset.email;
            }
        },

        token_reset() {
            if (this.tokenReset) {
                return this.tokenReset;
            } else {
                const dataReset = localStorage.getItem("reset-password")
                    ? JSON.parse(localStorage.getItem("reset-password"))
                    : null;
                return dataReset?.token_reset;
            }
        },
    },
};
</script>
