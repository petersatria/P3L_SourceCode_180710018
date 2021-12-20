<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Tambah Transaksi</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row my-5">
                    <v-btn icon color="indigo" light @click="back()">
                        <v-icon>mdi-arrow-left </v-icon>
                    </v-btn>
                </div>
                <v-container class="my-10 px-10">
                    <v-form :v-model="valid" lazy-validation ref="form">
                        <v-text-field label="Kode Customer" v-model="form.kode_cust"  :rules="requiredRule"></v-text-field>
                    </v-form>
                    <div class="d-flex flex-row-reverse my-5">
                        <v-btn color="blue darken-1" text @click="submit()">Selanjutnya</v-btn>
                    </div>
                </v-container>
            </v-card-text>
        </v-card>
        <v-snackbar v-model="snackbar" :color="snackabrColor" :multi-line="true" :timeout="3000">
            {{ message }}
            <template v-slot:action="{ attrs }">
                <v-btn dark text @click="snackbar = false" v-bind="attrs">
                    Tutup
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>
<script>
export default {
    data() {
        return{
            url : this.$apiUrl + "/Transaksi/",
             form: {            
                kode_cust : '',
                id_pegawai : '',
            }, 
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            snackbar : false,
            snackabrColor : null,
            message : '',
            
        }
    },
    methods : {       
        submit() {
            this.$refs.form.validate();
            if(this.$refs.form.validate()){
                this.setRequest();
                this.isLoading = true;
                this.sendRequest(this.url);
            }
        },
        back(){
            this.$router.push('/Cs/transaksi');
        },

        sendRequest(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.$session.set('kode_transaksi', response.data.message);
                    this.$router.push('/Cs/transaksi/tambah/detil');
                }else{
                    this.snackabrColor = 'error';
                }
                this.isLoading = false;               
                        
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';               
                this.isLoading = false;           
            })
        },

        closeDialog(){
            this.$refs.form.reset();
            this.form.password = '';
            this.dialog = false;
        },

        setRequest(){
            this.request.append('kode_cust', this.form.kode_cust);
            this.request.append('id_pegawai', this.form.id_pegawai);
        },
    },
    mounted(){
        this.form.id_pegawai = this.$session.get('pegawai');
        console.log(this.form.id_pegawai);
    }
}
</script>