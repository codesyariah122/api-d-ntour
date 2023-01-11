import Home from "./views/Home";
import Login from "./views/Auth/login";
import Admin from "./views/Dashboard/_role";
import Profile from "./views/Profile/_username";
import Success from "./views/Auth/success";
import Activated from "./views/Activated/index";

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
        name: "success",
        path: "/activated/:token",
        component: Activated,
    },
];
