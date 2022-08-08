import { createRouter, createWebHistory } from "vue-router";
import Formulaire from "@/components/formulaire/Formulaire.vue";
import Categorie from "@/components/categorie/Categorie.vue";
import ArticleModif from "@/components/articleModif/articleModif.vue";
const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/formulaire",
      name: "formulaire",
      component: Formulaire,
    },
    {
      path: "/categorie",
      name: "categorie",
      component: Categorie,
    },
    {
      name:"modifArticle",
      path: "/modifarticle",
      component: ArticleModif,
      props: true
    }
 
  ],
});

export default router;
