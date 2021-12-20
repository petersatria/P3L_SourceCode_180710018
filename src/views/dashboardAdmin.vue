<template>
    <v-app>
         <v-app-bar
      app
      color="red lighten-1"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-btn icon @click="logout()">
        <v-icon>mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>

    <v-navigation-drawer 
    app
    v-model="drawer">
      <v-list-item class="my-5">
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            Natural Beauty Center
          </v-list-item-title>
          <v-list-item-subtitle class="text-h7">
            Admin
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list
        dense
        nav
      >
        <v-list-item
        v-for="item in items"
        :key="item.title"
        link
        @click="$router.push(item.path)"
        >
        <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main class="grey lighten-4">
      <router-view/>
    </v-main>
    </v-app>
</template>

<script>

export default {
    name: 'app',
    data: () => ({
        drawer : false,
        items: [
            { title: 'Produk', path : '/admin/produk'},
            { title: 'Perawatan', path : '/admin/perawatan'},
            { title: 'Pegawai', path : '/admin/pegawai'},
            ],
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