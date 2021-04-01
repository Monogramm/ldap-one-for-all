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
    props: (route) => ({ subject: route.query.subject, message: route.query.message }),
  },
];
