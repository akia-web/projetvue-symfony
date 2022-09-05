import { createRouter, createWebHistory } from "vue-router";
import Formulaire from "@/components/formulaire/Formulaire.vue";
import Categorie from "@/components/categorie/Categorie.vue";
import ArticleModif from "@/components/articleModif/articleModif.vue";
import Inscription from "@/components/Inscription/inscription.vue";
import Connexion from "@/components/connexion/connexion.vue";
import Profil from "@/components/profil/profil.vue";
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
    },
    {
      name:"inscription",
      path: "/inscription",
      component: Inscription,
    },
    {
      name:"connexion",
      path: "/connexion",
      component: Connexion,
    },
    {
      name:"profil",
      path: "/profil",
      component: Profil,
    } 
  ],
});

export default router;
