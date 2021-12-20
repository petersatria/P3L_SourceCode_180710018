<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Tambah Dokter</v-card-title>
            <v-card-text>
                <v-container class="my-10 px-10">
                    <v-form :v-model="valid" lazy-validation ref="form">
                        <v-select
                            v-model="form.id_pegawai"
                            :items="namaDokter"
                            :rules="requiredRule"
                            label="Dokter"
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
            urlDokter : this.$apiUrl + "/Pegawai/Dokter/",
             form: {            
                kode_transaksi : '',
                id_pegawai : '',
            }, 
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            snackbar : false,
            snackabrColor : null,
            message : '',
            transaksi : null,
            kode_transaksi : '',
            dokter : [],
            namaDokter : [],
            
        }
    },
    methods : {     
        getDataTransaksi(){
            let uri = this.url+this.kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.transaksi = response.data.message
                    this.getDataDokter();
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataDokter(){
            let uri = this.urlDokter+this.transaksi.id_jadwal;
            console.log(uri);
            this.$http.get(uri).then(response => {
                    this.dokter = response.data.message
                    this.namaDokter = response.data.message.map(x=>x.nama_pegawai);
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        submit() {
            this.$refs.form.validate();
            if(this.$refs.form.validate()){
                this.setRequest();
                this.isLoading = true;
                this.sendRequest(this.url+'doctor/'+this.kode_transaksi);
            }
        },

        sendRequest(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.$router.push('/Cs/transaksi/');
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
            for(var i = 0; i < this.dokter.length; i++) {
                if (this.dokter.map(x => x.nama_pegawai)[i] === this.form.id_pegawai) {
                    this.request.append('id_pegawai',this.dokter.map(x => x.id_pegawai)[i]);
                }
            }
        },
    },
    mounted(){
        this.kode_transaksi = this.$session.get('kode_transaksi');
        this.getDataTransaksi();
    }
}
</script>