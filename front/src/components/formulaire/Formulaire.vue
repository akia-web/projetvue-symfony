<template>
  <div>
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

      <select v-model="categorie">
        <option disabled> choisir une categorie</option>
        <option v-for="item in categories">{{item.name}}</option>
      </select>

      
      <input type="submit" @click="send" />
    </div>


    <div>
      <p v-for="item in articles" :key="item.id">
        <span>{{item.titre}} | </span><span>{{item.description}}</span> | <span>{{item.categorie}}</span>
        <button @click="modifier(item)">Modifier</button>     
      </p>
    </div>
  </div>
  
</template>

<script>
import axios from "axios"
export default {
  name: "Formulaire",
  data() {
    return {
      titre: null,
      description: null,
      message: null,
      articles: [],
      categories:[],
      categorie: null
    };
  },
  methods: {
    async send() {

      if(this.categorie == null){
        this.message = "vous devez choisir une categorie";
        return
      }
     
      let article = {
        titre: this.titre,
        description: this.description,
        categorie: this.categorie

      };

      let url = "http://127.0.0.1:8000/api/articles";

      await axios.post(url, article).then(
        (res) => {
          this.message = "article envoyé";
          this.articles.push(res.data);
          this.titre = null,
          this.description = null
          this.categorie = null
        },
        (error) => {
          this.message = "erreur lors de l'enregistrement de l'article";
        }
      );
    },
   
    async getAllArticles(){
      const url = "http://127.0.0.1:8000/api/articles"
      await axios.get(url).then(
        (res)=>{
          console.log(res.data);
          this.articles = res.data
        },
        (error)=>{

        }
      )
    },

    async getAllCategories(){
      const url = "http://127.0.0.1:8000/api/categories";
      await axios.get(url).then(
        (res)=>{
          this.categories = res.data
          console.log(res.data);
        },
        (error)=>{

        }
      )
    },

    modifier(item){
      let sendData = JSON.stringify(item)
      this.$router.push({name: 'modifArticle', params: {data: sendData} })
    }

    
  },
  beforeMount(){
    this.getAllArticles();
    this.getAllCategories();
  }
};
</script>
