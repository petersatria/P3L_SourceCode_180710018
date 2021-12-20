<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Tambah Beautician</v-card-title>
            <v-card-text>
                <v-container class="my-10 px-10">
                    <v-form :v-model="valid" lazy-validation ref="form">
                        <v-select
                            v-model="form.id_pegawai"
                            :items="namaBeautician"
                            :rules="requiredRule"
                            label="Beautician"
                            required
                        ></v-select> 
                        <v-select
                            v-model="form.id_ruangan"
                            :items="namaRuangan"
                            :rules="requiredRule"
                            label="Ruangan"
                            required
                        ></v-select> 
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
            urlBeautician : this.$apiUrl + "/Pegawai/beautician/",
            urlRuangan : this.$apiUrl + '/Ruangan/',
            urlCust : this.$apiUrl + '/Customer/',
             form: {            
                id_ruangan : '',
                id_pegawai : '',
            }, 
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            snackbar : false,
            snackabrColor : null,
            message : '',
            transaksi : null,
            customer : null,
            kode_transaksi : '',
            beautician : [],
            namaBeautician : [],
            ruangan : [],
            namaRuangan : [],
            
        }
    },
    methods : {     
        getDataTransaksi(){
            let uri = this.url+this.kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.transaksi = response.data.message
                    this.getDataCust(this.transaksi.kode_cust);
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },
        getDataCust(kode_cust){
            let uri = this.urlCust+kode_cust;
            this.$http.get(uri).then(response => {
                    this.customer = response.data.message
                    this.getDataBeautician();
                    this.getDataRuangan();
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataBeautician(){
            let uri = this.urlBeautician;
            this.request.append('jk_pegawai', this.customer.jk_cust);
            this.request.append('id_jadwal', this.transaksi.id_jadwal);
            this.$http.post(uri,this.request).then(response => {
                    this.beautician = response.data.message
                    this.namaBeautician = response.data.message.map(x=>x.nama_pegawai);
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataRuangan(){
            this.$http.get(this.urlRuangan).then(response => {
                this.ruangan = response.data.message
                this.namaRuangan =  response.data.message.map(x=>x.no_ruangan);
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        submit() {
            this.$refs.form.validate();
            if(this.$refs.form.validate()){
                this.setRequest();
                this.isLoading = true;
                this.sendRequest(this.url+'beauticianCS/'+this.kode_transaksi);
            }
        },

        sendRequest(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.$router.push('/Dokter/');
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
        setRequest(){
            this.request.append('kode_transaksi', this.transaksi.kode_transaksi);
            this.request.append('id_pegawai', this.form.id_pegawai);
            for(var i = 0; i < this.beautician.length; i++) {
                if (this.beautician.map(x => x.nama_pegawai)[i] === this.form.id_pegawai) {
                    this.request.append('id_pegawai',this.beautician.map(x => x.id_pegawai)[i]);
                }
            }
            this.request.append('no_ruangan', this.form.id_ruangan)
        },
    },
    mounted(){
        this.kode_transaksi = this.$session.get('kode_transaksi');
        this.getDataTransaksi();
    }
}
</script>