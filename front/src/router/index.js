import { createRouter, createWebHistory } from "vue-router";
import Formulaire from "@/components/formulaire/Formulaire.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/formulaire",
      name: "formulaire",
      component: Formulaire,
    },
  ],
});

export default router;
