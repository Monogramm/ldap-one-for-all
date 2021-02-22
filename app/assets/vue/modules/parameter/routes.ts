import { RouteConfig } from "@/vue/interfaces/router";

import AdminParameters from "./views/admin/Parameters.vue";
import AdminParameter from "./views/admin/Parameter.vue";
//const AdminParameters = () => import(/* webpackChunkName: "AdminParameters" */ "./views/admin/Parameters.vue").then((m: any) => m.default);
//const AdminParameter = () => import(/* webpackChunkName: "AdminParameter" */ "./views/admin/Parameter.vue").then((m: any) => m.default);

export const ParameterRoutes: RouteConfig[] = [
  {
    name: "AdminParameters",
    path: "/admin/parameters",
    component: AdminParameters,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    name: "ParameterEdit",
    path: "/admin/parameter/:id",
    component: AdminParameter,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
    props: (route) => ({ id: route.params.id }),
  },
  {
    name: "ParameterCreate",
    path: "/admin/parameter",
    component: AdminParameter,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
];
