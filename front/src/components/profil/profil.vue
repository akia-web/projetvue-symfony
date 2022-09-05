<template>
    <div>
        <h1>bienvenue {{email}}</h1>
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
    }
  },
  beforeMount(){
    this.getUser();
  }
}
</script>