<template>
    <v-app>
      <v-app-bar
        app
        color="red lighten-1"
        dark
      >
        <v-spacer></v-spacer>
        <v-btn icon @click="logout()">
          <v-icon>mdi-logout</v-icon>
        </v-btn>
      </v-app-bar>

      <v-main class="grey lighten-4">
        <router-view/>
      </v-main>
    </v-app>
</template>

<script>

export default {
    name: 'app',
    data: () => ({
    }),
    methods : {
      logout(){
        this.$router.push('/');
        this.$session.clear();
      }
    },
    mounted(){
      if(this.$session.has('pegawai')){
        if(this.$session.get('role')=='Admin'){
          this.$router.push('/admin/produk');
        }else if(this.$session.get('role')=='Kepala Klinik'){
          this.$router.push('/kepalaKlinik/promo');
        }else if(this.$session.get('role')=='Customer Service'){
          this.$router.push('/Cs/customer');  
        }else if(this.$session.get('role')=='Dokter'){
          this.$router.push('/dokter/');  
        }else if(this.$session.get('role')=='Kasir'){
          this.$router.push('/kasir/');  
        }else if(this.$session.get('role')=='Beautician'){
          this.$router.push('/beautician/');  
        }else{
          this.$router.push('/')
        }
      }else{
        this.$router.push('/')
      }
    }
};
</script>