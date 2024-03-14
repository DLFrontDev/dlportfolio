import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: () => import("../views/HomeView.vue"),
    },
    {
      path: "/timeline",
      name: "timeline",
      component: () => import("../views/Timeline.vue"),
    },
  ],
});

export default router;
