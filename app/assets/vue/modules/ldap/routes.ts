import { RouteConfig } from "@/vue/interfaces/router";

export const LdapRoutes: RouteConfig[] = [
    {
      name: "",
      path: "",
      component: foo,
      meta: {
        requiresAuth: true,
        requiresAdmin: true,
      },
    }
]

function foo()
{
    
}