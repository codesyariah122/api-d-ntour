import Home from "./views/Home";
import Login from "./views/login";
import Admin from "./views/Dashboard/_role";

export const routes = [
    {
        name: "home",
        path: "/",
        component: Home,
    },
    {
        name: "login",
        path: "/login",
        component: Login,
    },
    {
        name: "admin",
        path: "/dashboard/:role",
        component: Admin,
    },
];
