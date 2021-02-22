import { RouteConfig } from "@/vue/interfaces/router";

import ContactSupport from "./views/ContactSupport.vue";

export const SupportRoutes: RouteConfig[] = [
  {
    name: "ContactSupport",
    path: "/contact",
    component: ContactSupport,
    meta: {
      requiresAuth: true,
    },
  },
];
