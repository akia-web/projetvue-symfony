<template>
  <div>
    <h1>Mes categories</h1>
    <h2 v-if="message != null">
      {{ message }}
    </h2>

    <input type="text" name="" id="" placeholder="nom de la catégorie" v-model="name" />
  
    <input type="submit" @click="send" />

    <div class="container">
      <p v-for="item in categories" :key="item.id">
        <span>{{item.name}}</span>
        <button  v-if="wantToModifCategorie != item.id" @click="deleteCategory(item.id)">x</button>
        
      </p>
    </div>

  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Categorie",
  data() {
    return {
      name: null,
      message: null,
      categories: [],
      
    };
  },
  methods: {
    async send() {
      let categorie = {
        name: this.name,
      };

      let url = "http://127.0.0.1:8000/api/categories";
      await axios.post(url, categorie).then(
        (res) => {
          this.message = "nouvelle catégorie enregistrée";
          this.name = null
          this.categories.push(res.data);
          // this.$router.push("/");
       
        },
        (error) => {
          this.message = "erreur lors de l'enregistrement de la categorie";
        }
      );
    },

    async getAllCategories(){
      const url = "http://127.0.0.1:8000/api/categories"
      await axios.get(url).then(
        (res)=>{
          console.log(res.data);
          this.categories = res.data
        },
        (error)=>{

        }
      )
    },

    async deleteCategory(id){
      let url = "http://127.0.0.1:8000/api/categories/"+id

      await axios.delete(url);

      let index = 0;
      let tableau = this.categories;

      for(let i = 0; i< tableau.length; i++ ){
        if(tableau[i].id == id){
          index = i;
        }
      }
     
      setTimeout(function(){
         tableau.splice(index, 1);
      }, 50)

    },

  },
  beforeMount(){
    this.getAllCategories();
  }

 
};
</script>
