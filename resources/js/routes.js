import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import Login from "./components/LoginComponent.vue";
import Register from "./components/RegisterComponent.vue";
import Profile from "./components/ProfileComponent.vue";

export const routes = [
    {
        path: "/",
        redirect: { name: "login" },
    },
    {
        name: "login",
        path: "/login",
        component: Login,
    },
    {
        name: "register",
        path: "/register",
        component: Register,
    },
    {
        name: "profile",
        path: "/profile",
        component: Profile,
    },
];
