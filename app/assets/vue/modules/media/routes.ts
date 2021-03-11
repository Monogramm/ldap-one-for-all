import { RouteConfig } from "@/vue/interfaces/router";

import AdminMedias from "./views/admin/Medias.vue";
import AdminMedia from "./views/admin/Media.vue";
//const AdminMedias = () => import(/* webpackChunkName: "AdminMedias" */ "./views/admin/Medias.vue").then((m: any) => m.default);
//const AdminMedia = () => import(/* webpackChunkName: "AdminMedia" */ "./views/admin/Media.vue").then((m: any) => m.default);

export const MediaRoutes: RouteConfig[] = [
  {
    name: "AdminMedias",
    path: "/admin/medias",
    component: AdminMedias,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    name: "MediaEdit",
    path: "/admin/media/:id",
    component: AdminMedia,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
    props: (route) => ({ id: route.params.id }),
  },
  {
    name: "MediaCreate",
    path: "/admin/media",
    component: AdminMedia,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
];
