<template>
  <div>
    <h1>Un formulaire</h1>
    <h2 v-if="message != null">
      {{ message }}
    </h2>

    <input type="text" name="" id="" placeholder="titre" v-model="titre" />
    <input
      type="text"
      name=""
      id=""
      placeholder="description"
      v-model="description"
    />
    <input type="submit" @click="send" />
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Formulaire",
  data() {
    return {
      titre: null,
      description: null,
      message: null,
    };
  },
  methods: {
    async send() {
      let article = {
        titre: this.titre,
        description: this.description,
      };

      let url = "http://127.0.0.1:8000/api/articles";

      await axios.post(url, article).then(
        (res) => {
          this.message = "article envoyé";
          // this.$router.push("/");
        },
        (error) => {
          this.message = "erreur lors de l'enregistrement de l'article";
        }
      );
    },
  },
};
</script>
