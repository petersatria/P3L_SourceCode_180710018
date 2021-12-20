<template>
  <v-app>
    <v-app-bar
        app
        color="red lighten-1"
        dark
      >
      <h3>Natural Beauty Center</h3>
      </v-app-bar>
    <v-main>
       <v-container fill-height fluid>
        <v-row align="center"
            justify="center"
            no-gutters>
            <v-col cols="4"></v-col>
            <v-col cols="4">
              <v-card max-width="600px">
                <v-card-title class="justify-center"><h3>Login</h3></v-card-title>
                <v-card-text>
                  <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-form :v-model="valid" lazy-validation ref="form">
                                  <v-text-field label="Username" v-model="form.username" :rules="requiredRule"></v-text-field>
                                  <v-text-field label="Password" :type="showPassword ? 'text' : 'password'" v-model="form.password" :rules="requiredRule" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword"></v-text-field>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="submit()">Masuk</v-btn>
                    </v-card-actions>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col cols="4"></v-col>
        </v-row>
         <v-snackbar v-model="snackbar" :color="snackabrColor" :multi-line="true" :timeout="3000">
            {{ message }}
            <template v-slot:action="{ attrs }">
                <v-btn dark text @click="snackbar = false" v-bind="attrs">
                    Tutup
                </v-btn>
            </template>
        </v-snackbar>
      </v-container>

    </v-main>
  </v-app>
</template>

<script>
export default {
  data(){
    return {
      form: {            
          username : '',
          password : '',
      }, 
      valid : true,
      requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
      showPassword : false,
      snackbar : false,
      snackabrColor : null,
      message : '',
      request : new FormData,
      url : this.$apiUrl + "/Pegawai/login",
    }
  },
  methods : {
     submit() {
            this.$refs.form.validate();
            if(this.$refs.form.validate()){
                this.request.append("username", this.form.username);
                this.request.append("password", this.form.password);
                this.$http.post(this.url, this.request).then(response => {
                  this.errorType = response.data.error;
                  if (this.errorType == true) {
                    this.snackbar = true;
                    this.message = response.data.message;
                    this.color = 'red';
                  } else {
                    // this.resetForm();
                    this.pegawai = response.data.message;
                    if(this.pegawai.id_role_pegawai == 'Admin'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/admin"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else if(this.pegawai.id_role_pegawai == 'Kepala Klinik'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/kepalaKlinik"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else if(this.pegawai.id_role_pegawai == 'Customer Service'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/Cs"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else if(this.pegawai.id_role_pegawai == 'Dokter'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/dokter"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else if(this.pegawai.id_role_pegawai == 'Kasir'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/kasir"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else if(this.pegawai.id_role_pegawai == 'Beautician'){
                        this.$session.set('pegawai', this.pegawai.id_pegawai);
                        this.$session.set('role', this.pegawai.id_role_pegawai);
                        this.$router.push({
                          path: "/beautician"
                        });
                        this.snackbar = true;
                        this.color = 'green';
                        this.message = 'Succses'
                    }else{
                        this.snackbar = true;
                        this.message = 'Maaf Anda Tidak Bisa Mengakses Menu Ini';
                        this.color = 'red';
                    }
                  }
                }).catch(error => {
                  this.errors = error;
                  this.snackbar = true;
                  this.text = error;
                  this.color = 'red';
                  this.load = false;
                });
            }
        },
  }
}
</script>
