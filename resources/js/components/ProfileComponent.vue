<template>
    <div class="profile_container">
        <nav class="navbar navbar-expand-lg justify-content-end px-5">
            <a
                href="javascript::void(0)"
                @click="logout"
                style="
                    text-decoration: none;
                    color: #d8bc98;
                    font-size: medium;
                    font-weight: bold;
                "
                >Logout</a
            >
        </nav>
        <div class="d-flex align-items-center justify-content-center">
            <div class="card py-5 px-5" style="width: 60%">
                <div class="d-flex align-items-center" style="column-gap: 20px">
                    <div class="profile_picture">
                        <img
                            :src="getImage(user.profile_picture)"
                            alt="Profile Image"
                            style="
                                height: 140px;
                                width: 140px;
                                border-radius: 50%;
                            "
                        />
                    </div>
                    <div class="profile_content_1">
                        <div>
                            <h1 style="font-weight: bolder !important">
                                {{ user.name }}
                            </h1>
                        </div>
                        <div class="bio">
                            <h6 class="mb-0 pb-0" style="font-weight: bold">
                                Bio
                            </h6>
                            <p>{{ user.bio }}</p>
                        </div>
                    </div>
                    <div class="profile_content_2">
                        <div class="phone">
                            <h6>Phone</h6>
                            <p>{{ user.phone }}</p>
                        </div>
                        <div class="email">
                            <h6>Email</h6>
                            <p>{{ user.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center mt-4">
            <div
                class="d-flex align-items-center justify-content-center flex-wrap"
                style="width: 60%; row-gap: 10px; column-gap: 10px"
            >
                <div
                    class="card"
                    v-for="(e, ikey) in albums"
                    :key="ikey"
                    style="width: 32%"
                >
                    <div class="d-flex flex-column">
                        <div style="position: relative">
                            <img
                                :src="getImage(e.img)"
                                :alt="e.title"
                                style="max-width: 294px; max-height: 250px"
                            />
                            <h4
                                style="
                                    font-weight: bold;
                                    position: absolute;
                                    top: 110px;
                                    color: #fff;
                                    margin-left: 10px;
                                "
                            >
                                {{ e.title }}
                            </h4>
                        </div>
                        <div class="album_content">
                            <p
                                class="px-4 pt-2 mb-2"
                                style="text-align: justify"
                            >
                                {{ e.description }}
                            </p>
                            <p class="text-end px-4" style="color: #979797">
                                {{ e.date }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "profile",
    data() {
        return {
            user: [],
            albums: [],
            AuthStr: "Bearer " + this.$store.state.token,
            baseUrl: this.$route.path,
        };
    },
    mounted() {
        if (this.$store.state.token !== "") {
            axios.defaults.headers.common["Authorization"] = this.AuthStr;
            axios
                .post("/api/checkToken")
                .then((response) => {
                    if (!response.data.success) {
                        this.$store.state.commit(
                            "setToken",
                            response.data.token
                        );
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.$store.commit("clearToken");
                    this.$router.push({ name: "login" });
                });
        } else {
            this.$router.push({ name: "login" });
            this.loading = false;
        }
        this.getProfile();
    },
    methods: {
        async getProfile() {
            axios.defaults.headers.common["Authorization"] = this.AuthStr;
            await axios
                .get("/api/profile")
                .then((response) => {
                    this.user = response.data.user;
                    this.albums = response.data.albums;
                })
                .catch((error) => {
                    console.log(error.response);
                    this.user = [];
                    this.albums = [];
                });
        },
        async logout() {
            axios.defaults.headers.common["Authorization"] = this.AuthStr;
            await axios
                .post("/api/logout")
                .then((response) => {
                    this.$store.commit("clearToken");
                    this.$router.push({ name: "login" });
                })
                .catch((error) => {
                    console.log(error.response);
                });
        },
        getImage(url) {
            if (url.match(this.baseUrl)) {
                return url;
            } else {
                return this.baseUrl + url;
            }
        },
    },
};
</script>
