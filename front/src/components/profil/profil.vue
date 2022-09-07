<template>
    <div>
        <h1>bienvenue {{email}}</h1>
        <img v-if="image != null" :src="image" alt="">
        <!-- <input type="file" @change="sendImage"> <br> -->

        <input type="file" id="lala" style="display:none" @change="sendImage">
        <button onclick="document.getElementById('lala').click()">Changer de photo de profil</button>
        <button @click="deconnexion">deconnexion</button>
    </div>

</template>

<script>
import axios from "axios"
export default {
  name: "Profil",
  data() {
    return {
        email: null,
        token: localStorage.getItem("tokenProjet"),
        image: null
    };
  },
  methods:{
    async getUser(){
        const url = "http://127.0.0.1:8000/api/user?token="+this.token
        await axios.get(url).then(
            (res)=>{
                if(res.data.role != 'user'){
                    this.$router.push("/connexion")
                }
                this.email = res.data.email
                this.image = res.data.image
                console.log(this.image)
            },
            (error)=>{

            }
        )
    },

    async deconnexion(){
        const url = "http://127.0.0.1:8000/api/deconnexion?token="+this.token
        await axios.get(url)
        localStorage.removeItem("tokenProjet");
        this.$router.push("/connexion")
    },

   async sendImage(e){
     const file = e.target.files[0];
     console.log(file)
     if(file.type == "image/png" || file.type == "image/jpeg"){
        const data = new FormData();
        data.append('image', file);
        data.append('token', this.token )
        const url = "http://127.0.0.1:8000/api/image-profil"
        await axios.post(url, data).then(
          (res)=>{
            this.image = res.data
            console.log(res.data)
          },
          (error)=>{

          }
        )

     }

    }
  },
  beforeMount(){
    this.getUser();
  }
}
</script>