import Home from "./views/Home";
import Login from "./views/Auth/login";
import Admin from "./views/Dashboard/_role";
import Profile from "./views/Profile/_username";
import Success from "./views/Auth/success";
import Activated from "./views/Activated/index";
import Forgot from "./views/Auth/forgot/index";
import Reset from "./views/Auth/reset/_token-reset";
import PrivacyPolicy from "./views/PrivacyPolicy";

export const routes = [
    {
        name: "home",
        path: "/",
        component: Home,
    },
    {
        name: "login",
        path: "/auth/login",
        component: Login,
    },
    {
        name: "admin",
        path: "/dashboard/:role",
        component: Admin,
    },
    {
        name: "customer",
        path: "/profile/:username",
        component: Profile,
    },
    {
        name: "success",
        path: "/auth/success",
        component: Success,
    },
    {
        name: "activated-success",
        path: "/activated/:token",
        component: Activated,
    },
    {
        name: "forgot",
        path: "/auth/forgot",
        component: Forgot,
    },
    {
        name: "reset",
        path: "/auth/reset/:token_reset",
        component: Reset,
    },
    {
        name: "privacy",
        path: "/privacy-policy",
        component: PrivacyPolicy,
    },
];
