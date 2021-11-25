import { RouteConfig } from "@/vue/interfaces/router";

// TODO Move this to AuthModule
import Registration from "./views/Registration.vue";
import Login from "./views/Login.vue";

import UserProfile from "./views/UserProfile.vue";
import VerifyAccount from "./views/VerifyAccount.vue";
import ResetPassword from "./views/ResetPassword.vue";

import AdminUsers from "./views/admin/Users.vue";
import AdminUser from "./views/admin/User.vue";
//const AdminUsers = () => import(/* webpackChunkName: "AdminUsers" */ "./views/admin/Users.vue").then((m: any) => m.default);
//const AdminUser = () => import(/* webpackChunkName: "AdminUser" */ "./views/admin/User.vue").then((m: any) => m.default);

export const UserRoutes: RouteConfig[] = [
  {
    name: "Registration",
    path: "/registration",
    component: Registration,
    meta: {
      anonymousOnly: true,
    },
  },
  {
    name: "Login",
    path: "/login",
    component: Login,
    meta: {
      anonymousOnly: true,
    },
    props: (route) => ({ passwordReset: route.query.resetPassword == "1" }),
  },
  {
    name: "ResetPassword",
    path: "/password/reset",
    component: ResetPassword,
    meta: {
      anonymousOnly: true,
    },
  },
  {
    name: "VerifyAccount",
    path: "/verify",
    component: VerifyAccount,
    meta: {
      requiresAuth: true,
      unverified: true,
    },
  },
  {
    name: "UserProfile",
    path: "/profile",
    component: UserProfile,
    meta: {
      requiresAuth: true,
      unverified: true,
    },
  },
  {
    name: "AdminUsers",
    path: "/admin/users",
    component: AdminUsers,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
      adminDashboard: {
        label: "users.admin",
        icon: "users",
      },
    },
  },
  {
    name: "UserEdit",
    path: "/admin/user/:id",
    component: AdminUser,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
    props: (route) => ({ id: route.params.id }),
  },
];
