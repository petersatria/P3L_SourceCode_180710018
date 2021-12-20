<template>
    <v-container>
        <v-card class="mt-1 pa-10" v-if="transaksi!=null">
            <v-card-title
            class="font-weight-black d-flex justify-center text">Transaksi : {{transaksi.kode_transaksi}}</v-card-title>
            <v-card-text>
                <v-container>
                    <h2 class="mb-3">Data Customer : </h2>
                    <table>
                        <tr>
                            <td>Nama </td>
                            <td>: {{customer.nama_cust}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin </td>
                            <td>: {{customer.jk_cust}}</td>
                        </tr>
                    </table>
                    <h2 class="my-3">Data Ruangan : </h2>
                    <table>
                        <tr>
                            <td>Ruangan</td>
                            <td>: {{ruangan.no_ruangan}}</td>
                        </tr>
                    </table>
                    <h3 class="mt-10 mb-3">Daftar Perawatan :</h3>
                    <v-data-table
                    :headers="headersDetilPerawatan"
                    :items="detilPerawatan"
                    :items-per-page="5"
                    :loading="isLoading"
                    class="elevation-1">
                        <template v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="(item,index) in items" :key="item.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.nama_prw }}</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                    <div class="d-flex flex-row-reverse my-10">
                        <v-btn color="blue darken-1" text @click="done()">Selesai</v-btn>
                    </div>
                </v-container>
            </v-card-text>
        </v-card>
        <v-card v-else>
            <v-card-title
            class="font-weight-black d-flex justify-center text">Belum Terdapat Antrian</v-card-title>
            <v-card-text>
                <v-container fill-height fluid>
                    <v-row align="center"
                        justify="center">
                        <v-btn
                        color="red lighten-1"
                        class="ma-2 white--text"
                        @click="getDataTransaksi()"
                        >
                        <v-icon
                            right
                            dark
                        >
                            mdi-refresh
                        </v-icon>
                        <span class="ml-2">Refresh</span>
                        
                        </v-btn>
                    </v-row>
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
            url : this.$apiUrl + "/Transaksi/beautician/",
            urlDetilPerawatan : this.$apiUrl + '/DetilTransaksiPerawatan/',
            urlCust : this.$apiUrl + '/Customer/',
            urlRuangan : this.$apiUrl + '/DetilRuanganTransaksi/',
            urlDone : this.$apiUrl + '/Transaksi/done/',
            headersDetilPerawatan : [
                {text : "No", value : "no"},
                {text : "Nama Perawatan", value : "nama_prw"},
            ],
            id_pegawai : null,
            transaksi : null,
            customer : null,
            ruangan : null,
            request : new FormData,
            detilPerawatan : [],
            riwayat : [],
            isLoading : true,
            snackbar : false,
            snackabrColor : null,
            message : '',
            
        }
    },
    methods : {
        getDataTransaksi(){
            let uri = this.url+this.id_pegawai;
            console.log(uri)
            this.$http.get(uri).then(response => {
                    this.transaksi = response.data.message
                    if(this.transaksi !=null){
                        this.getDataCust(this.transaksi.kode_cust)
                        this.getDataDetilPerawatan(this.transaksi.kode_transaksi);
                        this.getDataRuangan(this.transaksi.kode_transaksi);
                        
                    }
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
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataRuangan(kode_transaksi){
            let uri = this.urlRuangan+kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.ruangan = response.data.message
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataDetilPerawatan(kode_transaksi){
            let uri = this.urlDetilPerawatan+kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.detilPerawatan = response.data.message
                    this.isLoading = false
                    this.isEmpty = false;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        done(){
            this.request.append('no_ruangan', this.ruangan.no_ruangan);
            this.request.append('id_pegawai', this.id_pegawai);
            let uri = this.urlDone+this.transaksi.kode_transaksi;
            this.$http.post(uri,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.getDataTransaksi();
                    this.snackabrColor = 'success';
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
    },
    mounted(){
        this.id_pegawai = this.$session.get('pegawai');
        this.getDataTransaksi();
    }
}
</script>