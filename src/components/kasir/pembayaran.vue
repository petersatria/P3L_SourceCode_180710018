<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Transaksi</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row my-5">
                    <v-btn icon color="indigo" light @click="back()">
                        <v-icon>mdi-arrow-left </v-icon>
                    </v-btn>
                </div>
                <v-container>
                    <table>
                        <tr>
                            <td class="font-weight-bold"><h3>Kode</h3> </td>
                            <td class="font-weight-bold">: {{transaksi.kode_transaksi}}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold"><h3>Customer</h3> </td>
                            <td class="font-weight-bold">: {{customer.nama_cust}}</td>
                        </tr>
                    </table>
                    <h3 class="my-5">Daftar Produk :</h3>
                    <v-data-table
                    :headers="headersDetilProduk"
                    :items="detilProduk"
                    :items-per-page="5"
                    :loading="isLoading"
                    :search="searchDetilProduk"
                    class="elevation-1">
                        <template v-slot:top>
                            <v-text-field
                            append-icon="mdi-magnify"
                            label="Cari"
                            v-model="searchDetilProduk"
                            class="mx-4"
                            ></v-text-field>
                        </template>
                        <template v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="(item,index) in items" :key="item.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.nama_prd }}</td>
                                    <td>{{item.jumlah_prd}}</td>
                                    <td>{{ item.sub_total_prd }}</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                    <h3 class="mt-10 mb-5">Daftar Perawatan :</h3>
                    <v-data-table
                    :headers="headersDetilPerawatan2"
                    :items="detilPerawatan"
                    :items-per-page="5"
                    :loading="isLoading"
                    :search="searchDetilPerawatan"
                    class="elevation-1">
                        <template v-slot:top>
                            <v-text-field
                            append-icon="mdi-magnify"
                            label="Cari"
                            v-model="searchDetilPerawatan"
                            class="mx-4"
                            ></v-text-field>
                        </template>
                        <template v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="(item,index) in items" :key="item.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.nama_prw }}</td>
                                    <td>{{item.jumlah_prw}}</td>
                                    <td>{{ item.sub_total_prw }}</td>
                                    <td v-if="formPromo.kode_promo == 'POIN' ">
                                        <v-btn icon color="indigo" light @click="poin(item)" v-if="(item.poin_prw * item.jumlah_prw) > +customer.poin_cust || item.isPoinUsed == '1'" disabled>
                                            <v-icon>mdi-check</v-icon>
                                        </v-btn>
                                        <v-btn icon color="indigo" light @click="poin(item)" v-else>
                                            <v-icon>mdi-check</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>

                    

                    
                    <v-row class="mt-10">
                        <v-col cols="2"> 
                            <h3>Promo :</h3>
                        </v-col>
                        <v-col cols="7">
                            <v-select
                                v-model="formPromo.kode_promo"
                                :items="promos"
                                :rules="requiredRule"
                                label=""
                                required
                                dense
                                @change="getDataPromo(formPromo.kode_promo)"
                            ></v-select> 
                        </v-col>
                    </v-row>

                    <div class="d-flex flex-row-reverse mt-10 mb-5">
                        <table>
                            <tr>
                                <td class="font-weight-bold"><h3>Discount</h3> </td>
                                <td class="font-weight-bold">: Rp {{formPromo.diskon}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold"><h3>Total</h3> </td>
                                <td class="font-weight-bold">: Rp {{formPromo.total}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="d-flex flex-row-reverse mt-10">
                        <v-btn color="blue darken-1" text @click="submitDone()">Selesai</v-btn>
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
            urlPerawatan : this.$apiUrl + '/Perawatan/',
            urlDetilProduk : this.$apiUrl + '/DetilTransaksiProduk/',
            urlDetilPerawatan : this.$apiUrl + '/DetilTransaksiPerawatan/',
            urlCust : this.$apiUrl + '/Customer/',
            urlPromo : this.$apiUrl + '/Promo/',
            headersDetilProduk : [
                {text : "No", value : "no"},
                {text : "Nama Produk", value : "id_produk"},
                {text : "Jumlah", value : "jumlah_prd"},
                {text : "Sub Total", value : "sub_total_prd"},
            ],
            headersDetilPerawatan : [
                {text : "No", value : "no"},
                {text : "Nama Perawatan", value : "nama_prw"},
                {text : "Jumlah", value : "jumlah_prw"},
                {text : "Sub Total", value : "sub_total_prw"},
                {text : "Aksi"}
            ],
            headersDetilPerawatan2 : [
                {text : "No", value : "no"},
                {text : "Nama Perawatan", value : "nama_prw"},
                {text : "Jumlah", value : "jumlah_prw"},
                {text : "Sub Total", value : "sub_total_prw"},
            ],
            formPerawatan: {            
                kode_transaksi : '',  
                poin : '',
            }, 
            formPromo: {            
                kode_transaksi : '',  
                kode_promo : null,
                id_pegawai : '',
                total : 0,
                diskon : 0,
            }, 
            searchDetilPerawatan : '',
            searchDetilProduk : '',
            searchPerawatan : '',
            searchProduk : '',
            kode_transaksi : null,
            transaksi : null,
            customer : null,
            promo : null,
            pegawai : null,
            detilProduk : [],
            detilPerawatan : [],
            promos : [],
            isLoading : true,
            dialogProdukList: false,
            dialogProduk: false,
            dialogPerawatanList: false,
            dialogPerawatan: false,
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            notMinRule : [v => v>0 || 'Data Tidak Boleh Negatif',],
            titleDialog : "Tambah Data Pegawai",
            snackbar : false,
            snackabrColor : null,
            message : '',
            isEdit : false,
            updateId : '',
            showPassword : false,
            isEmpty : true,
            
        }
    },
    methods : {
        getDataTransaksi(){
            let uri = this.url+this.kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.transaksi = response.data.message
                    this.getDataCust(this.transaksi.kode_cust)
                    this.getDataPromo(this.transaksi.kode_promo)
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
                    this.getDataListPromo(this.customer.tgl_lahir_cust)
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataPromo(kode_promo){
            let uri = this.urlPromo+kode_promo;
            this.$http.get(uri).then(response => {
                    this.promo = response.data.message
                    this.formPromo.kode_transaksi = this.kode_transaksi
                    if(this.promo !=null){
                        this.formPromo.kode_promo = this.promo.kode_promo
                        this.formPromo.diskon = (this.promo.diskon * this.transaksi.total).toFixed(0)
                    }
                    this.formPromo.total = this.transaksi.total - this.formPromo.diskon
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataListPromo(tgl_cust){
            let uri = this.urlPromo+'cashier/'+tgl_cust;
            console.log(uri)
            this.$http.get(uri).then(response => {
                    this.promos = response.data.message.map(x=>x.kode_promo);
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';      
            })
        },

        getDataDetilProduk(){
            let uri = this.urlDetilProduk+this.kode_transaksi;
            this.$http.get(uri).then(response => {
                    this.detilProduk = response.data.message
                    this.isLoading = false
                    this.isEmpty = false;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataDetilPerawatan(){
            let uri = this.urlDetilPerawatan+this.kode_transaksi;
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


        poin(item){
            this.formPerawatan.kode_transaksi = this.kode_transaksi;
            this.formPerawatan.poin = item.poin_prw * item.jumlah_prw;
            this.sendRequestPoin(this.urlDetilPerawatan+'usedPoin/'+item.id_detil_transaksi_perawatan);
        },

        sendRequestPoin(url){
            this.request.append('kode_transaksi', this.formPerawatan.kode_transaksi);
            this.request.append('poin', this.formPerawatan.poin);
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';
                    this.getDataDetilPerawatan();
                    this.getDataCust();
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

        submitDone(){
            this.request.append('kode_promo', this.formPromo.kode_promo);
            this.request.append('total', this.formPromo.total);
            this.request.append('id_pegawai', this.pegawai);
            this.request.append('poin',(this.formPromo.total/50000-0.5).toFixed(0))
            if(this.detilPerawatan.length != 0){
                this.request.append('isDone', 0);
            }else{
                this.request.append('isDone', 1);
            }
            let uri = this.url + 'pay/' + this.kode_transaksi;
            this.$http.post(uri,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    window.open(this.$apiUrl+'/Nota/Nota?kode='+this.kode_transaksi);
                    this.$router.push('/kasir');
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
         
        back(){
            this.$router.push('/kasir');
        }
    },
    mounted(){
        this.kode_transaksi = this.$session.get('kode_transaksi');
        this.pegawai = this.$session.get('pegawai');
        this.getDataTransaksi();
        this.getDataDetilProduk();
        this.getDataDetilPerawatan();
    }
}
</script>