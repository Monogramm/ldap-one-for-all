import { RouteConfig } from "@/vue/interfaces/router";

import BackgroundJobs from "./views/admin/BackgroundJobs.vue";
//const BackgroundJobs = () => import(/* webpackChunkName: "AdminUsers" */ "./views/admin/BackgroundJobs.vue").then((m: any) => m.default);

export const BackgroundJobRoutes: RouteConfig[] = [
  {
    name: "BackgroundJobs",
    path: "/admin/background-jobs",
    component: BackgroundJobs,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
];
