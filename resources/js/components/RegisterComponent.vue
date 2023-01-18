<template>
    <div
        class="register_container d-flex align-items-center justify-content-center"
    >
        <div class="card py-4 px-2" style="width: 45%">
            <div class="d-flex flex-column align-items-center">
                <div class="logo mb-2">
                    <img
                        src="/img/cg-logo.png"
                        alt="CG LOGO"
                        style="height: 80px; width: 80px"
                    />
                </div>
                <h3 style="font-weight: 600">Register an account</h3>
                <p class="mb-0">
                    Have an Account?
                    <router-link
                        :to="{ name: 'login' }"
                        style="text-decoration: none"
                        >Login</router-link
                    >
                </p>
                <div class="card-body" style="width: 80%">
                    <form @submit.prevent="register">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Full Name"
                                        v-model="user.name"
                                    />
                                    <div
                                        v-if="errors.name.length > 0"
                                        v-for="(e, ikey) in errors.name"
                                        :key="ikey"
                                    >
                                        <span class="text-danger text-sm"
                                            >{{ e }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="smith@gmail.com"
                                        v-model="user.email"
                                    />
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
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Phone Number"
                                        maxLength="10"
                                        v-model="user.phone"
                                        v-on:keypress="isNumber($event)"
                                    />
                                    <div
                                        v-if="errors.phone.length > 0"
                                        v-for="(e, ikey) in errors.phone"
                                        :key="ikey"
                                    >
                                        <span class="text-danger text-sm"
                                            >{{ e }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Bio</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Bio"
                                        v-model="user.bio"
                                    />
                                    <div
                                        v-if="errors.bio.length > 0"
                                        v-for="(e, ikey) in errors.bio"
                                        :key="ikey"
                                    >
                                        <span class="text-danger text-sm"
                                            >{{ e }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        placeholder="**************"
                                        v-model="user.password"
                                    />
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
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        placeholder="**************"
                                        v-model="user.password_confirmation"
                                    />
                                    <div
                                        v-if="
                                            errors.password_confirmation
                                                .length > 0
                                        "
                                        v-for="(
                                            e, ikey
                                        ) in errors.password_confirmation"
                                        :key="ikey"
                                    >
                                        <span class="text-danger text-sm"
                                            >{{ e }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center col-12 mt-3">
                                <button
                                    type="submit"
                                    class="btn"
                                    style="
                                        background-color: #ff6c61;
                                        color: #fff;
                                        width: 20%;
                                        height: 120%;
                                    "
                                >
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.row {
    flex-direction: row;
}
</style>

<script>
import axios from "axios";

export default {
    name: "register",
    data() {
        return {
            user: {
                name: "",
                email: "",
                phone: "",
                bio: "",
                password: "",
                password_confirmation: "",
            },
            errors: "",
        };
    },
    mounted() {
        if (this.$store.state.token !== "") {
            this.$router.push({ name: "profile" });
        } else {
            this.loading = false;
        }
    },
    methods: {
        async register() {
            await axios
                .post("/api/register", this.user)
                .then((response) => {
                    console.log(response);

                    this.$store.commit("setToken", response.data.token);
                    this.$router.push({ name: "profile" });
                })
                .catch((error) => {
                    console.log(error.response.data.errors);
                    this.errors = error.response.data.errors;
                });
        },
        isNumber: function (event) {
            event = event ? event : window.event;
            var charCode = event.which ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            } else {
                return true;
            }
        },
    },
};
</script>
