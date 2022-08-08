<template>
    <div>
        <h1>Mondification de l'article {{article.titre}}</h1>
        <input type="text" v-model="article.titre" >
        <textarea name=""  v-model="article.description"></textarea>
        <p>ancienne categorie : {{article.categorie}}</p>
        <select v-model="article.categorie" >
            <option value="">choisir une categorie</option>
               <option v-for="item in categories">
              {{ item.name }}
            </option>
        </select>

        <button @click="modifier">Envoyer</button>
    </div>
</template>

<script>
import axios from "axios";
export default{
    name: "ArticleModif",
    data(){
        return{
            article: null,
            categories:[]
        };
    },
    methods:{
      getInfo(){
        let transformeDataToObject = JSON.parse(this.data);
        this.article = transformeDataToObject;
      },
      async getAllCategories(){
        const url = "http://127.0.0.1:8000/api/categories"
        await axios.get(url).then(
            (res)=>{
                this.categories = res.data
                console.log(res.data)
            },
            (error)=>{

            }

           
        )
      }, 
      async modifier(){
        const url = "http://127.0.0.1:8000/api/articles/"+this.article.id
        await axios.put(url, this.article).then(
            (res)=>{
                this.$router.push("/formulaire")
            },
            (error)=>{

            }
        )
      }
    },
    beforeMount(){
        this.getInfo(),
        this.getAllCategories();
    },
    props: {
        data: String
    }
}



</script>