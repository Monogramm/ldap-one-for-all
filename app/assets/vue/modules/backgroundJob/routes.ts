import { RouteConfig } from "@/vue/interfaces/router";

import BackgroundJobs from "./views/admin/BackgroundJobs.vue";
//const BackgroundJobs = () => import(/* webpackChunkName: "AdminBackgroundJobs" */ "./views/admin/BackgroundJobs.vue").then((m: any) => m.default);

export const BackgroundJobRoutes: RouteConfig[] = [
  {
    name: "AdminBackgroundJobs",
    path: "/admin/background-jobs",
    component: BackgroundJobs,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
      adminDashboard: {
        label: "background-jobs.admin",
        icon: "clock",
      },
    },
  },
];
