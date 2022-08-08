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
        <span @dblclick="wantToModifyCategorie = item.id" v-if="wantToModifyCategorie != item.id">{{item.name}}</span>
        <input @keyup.enter="modifier(item.id)" v-model="modifyName" v-if="wantToModifyCategorie == item.id" type="text" name="" id="" @keyup.esc="wantToModifyCategorie = null">
        <button @click="deleteCategory(item.id)">x</button>
        
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
      wantToModifyCategorie: null, //10
      modifyName: null,
      
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
   async modifier(id){
      const url = "http://127.0.0.1:8000/api/categories/"+id;
      let data = {"name": this.modifyName};
      
      await axios.put(url, data).then(
        (res)=>{

        },
        (error)=>{

        }

      )
      for(let i = 0; i<this.categories.length; i++){
        if(this.categories[i].id == this.wantToModifyCategorie){
          this.categories[i].name = this.modifyName;
        }

      }
      this.wantToModifyCategorie = null;
      this.modifyName = null
    }

  },

 
  beforeMount(){
    this.getAllCategories();
  }

 
};
</script>
