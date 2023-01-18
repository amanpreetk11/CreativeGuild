<template>
    <div
        class="login_container d-flex align-items-center justify-content-center"
    >
        <div class="card py-4 px-5" style="width: 28%">
            <div class="d-flex flex-column align-items-center">
                <div class="logo">
                    <img
                        src="/img/cg-logo.png"
                        alt="CG LOGO"
                        style="height: 80px; width: 80px"
                    />
                </div>
                <p class="mt-4 mb-0">
                    Don't have an Account?
                    <router-link
                        :to="{ name: 'register' }"
                        style="text-decoration: none"
                        >Create an Account</router-link
                    >
                </p>
                <div class="card-body">
                    <form @submit.prevent="login">
                        <div
                            v-if="invalid_credentials_error"
                            class="alert alert-danger"
                            role="alert"
                        >
                            {{ invalid_credentials_error }}
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="smith@gmail.com"
                                        v-model="user.email"
                                    />
                                    <div v-if="errors">
                                        <div
                                            v-if="errors.email.length > 0"
                                            v-for="(e, ikey) in errors.email"
                                            :key="ikey"
                                        >
                                            <span class="text-danger text-sm"
                                                >{{ e }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Password</label>
                                    <div style="position: relative">
                                        <input
                                            :type="passwordText"
                                            class="form-control"
                                            placeholder="**************"
                                            v-model="user.password"
                                        />
                                        <div
                                            style="
                                                position: absolute;
                                                top: 6px;
                                                right: 10px;
                                            "
                                        >
                                            <a
                                                id="show"
                                                href="javascript::void(0)"
                                                @click="
                                                    passwordType(passwordText)
                                                "
                                                v-if="show"
                                                ><img
                                                    src="/img/eye.svg"
                                                    alt="HIde"
                                            /></a>
                                            <a
                                                id="hide"
                                                href="javascript::void(0)"
                                                @click="
                                                    passwordType(passwordText)
                                                "
                                                v-if="hide"
                                                ><img
                                                    src="/img/eye-slash.svg"
                                                    alt="HIde"
                                            /></a>
                                        </div>
                                    </div>
                                    <div v-if="errors">
                                        <div
                                            v-if="errors.password.length > 0"
                                            v-for="(e, ikey) in errors.password"
                                            :key="ikey"
                                        >
                                            <span class="text-danger text-sm"
                                                >{{ e }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center col-12 mt-3">
                                <button
                                    type="submit"
                                    class="btn"
                                    style="
                                        background-color: #ff6c61;
                                        color: #fff;
                                        width: 30%;
                                        height: 120%;
                                    "
                                >
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "login",
    data() {
        return {
            user: {
                email: "",
                password: "",
            },
            errors: "",
            invalid_credentials_error: "",
            AuthStr: "Bearer " + this.$store.state.token,
            passwordText: "password",
            show: false,
            hide: true,
        };
    },
    mounted() {
        if (this.$store.state.token !== "") {
            axios.defaults.headers.common["Authorization"] = this.AuthStr;
            axios
                .post("/api/checkToken")
                .then((response) => {
                    if (response) {
                        this.$router.push({ name: "profile" });
                    } else {
                        this.$store.state.commit(
                            "setToken",
                            response.data.token
                        );
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.$store.commit("clearToken");
                });
        } else {
            this.loading = false;
        }
    },
    methods: {
        async login() {
            await axios
                .post("/api/login", this.user)
                .then((response) => {
                    console.log(response.data.token);

                    this.$store.commit("setToken", response.data.token);
                    this.$router.push({ name: "profile" });
                })
                .catch((error) => {
                    console.log(error.response.status);
                    if (error.response.status == 400) {
                        this.invalid_credentials_error = "Invalid Credentials.";
                    } else {
                        console.log(error.response.data.errors);
                        this.errors = error.response.data.errors;
                    }
                });
        },
        passwordType(passwordText) {
            if (passwordText == "password") {
                this.passwordText = "text";
                this.show = true;
                this.hide = false;
            } else {
                this.passwordText = "password";
                this.hide = true;
                this.show = false;
            }
        },
    },
};
</script>
