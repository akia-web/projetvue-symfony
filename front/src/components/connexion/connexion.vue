<template>
    <div>
        <h1>Connexion</h1>
        <p v-if="message != null">{{message}}</p>
    
        <input type="email" placeholder="email" v-model="email">
        <input type="password"  v-model="password">
        <button @click="envoyer">Envoyer</button>

    </div>
</template>

<script>
import axios from "axios"
export default {
  name: "Connexion",
  data() {
    return {
        email: null,
        password : null,
        message:null,
    };
  },
    methods:{
        async envoyer(){
            this.message = null;
            const regexEmail = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if(!this.email){
                return this.message = "renseignez l'email";
            }else if(!regexEmail.test(this.email)){
                return this.message = "email invalide";
            }else if(!this.password){
                return this.message = 'renseignez un mot de passe'
            }else if(this.password.length < 4 ){
                return this.message = "le mot de passe est trop court"
            }


            if(this.message == null){
                const url = "http://127.0.0.1:8000/api/connexion";
                const user =  {
                    "email": this.email,
                    "password": this.password
                }

                await axios.post(url,user).then(
                    (res)=>{
                        localStorage.setItem("tokenProjet", res.data)
                        this.$router.push("/profil")
                    },
                    (error)=>{
                        this.message = "une erreur est survenue lors de la connexion"
                    }
                )
            }
        
        }
    }
  }

</script>