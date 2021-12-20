<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Transaksi</v-card-title>
            <v-card-text>
                <v-container>
                    <h3>Kode : {{transaksi.kode_transaksi}}</h3>
                    <h3>Customer : {{customer.nama_cust}}</h3>
                    <h3 class="mt-5">Daftar Produk :</h3>
                    <div class="d-flex flex-row-reverse my-5">
                        <v-btn class="red lighten-1" dark @click="addProduk()">Tambah Produk</v-btn>
                    </div>
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
                                    <td>
                                        <v-btn icon color="indigo" light @click="editProduk(item)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn icon color="error" light @click="deleteProduk(item)">
                                            <v-icon>mdi-close-thick </v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                    <h3 class="mt-10">Daftar Perawatan :</h3>
                    <div class="d-flex flex-row-reverse my-5">
                        <v-btn class="red lighten-1" dark @click="addPerawatan()">Tambah Perawatan</v-btn>
                    </div>
                    <v-data-table
                    :headers="headersDetilProduk"
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
                                    <td>
                                        <v-btn icon color="indigo" light @click="editPerawatan(item)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn icon color="error" light @click="deletePerawatan(item)">
                                            <v-icon>mdi-close-thick </v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                    <div class="d-flex flex-row-reverse my-10">
                        <v-btn color="blue darken-1" text @click="done()">Selanjutnya</v-btn>
                    </div>
                </v-container>
            </v-card-text>
        </v-card>
        <v-dialog v-model="dialogProdukList" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <h5>Pilih Produk</h5>
                                 <v-data-table
                                    :headers="headersListProduk"
                                    :items="produk"
                                    :items-per-page="5"
                                    :loading="isLoading"
                                    :search="searchProduk"
                                    class="elevation-1">
                                        <template v-slot:top>
                                            <v-text-field
                                            append-icon="mdi-magnify"
                                            label="Cari"
                                            v-model="searchProduk"
                                            class="mx-4"
                                            ></v-text-field>
                                        </template>
                                        <template v-slot:body="{ items }">
                                            <tbody>
                                                <tr v-for="(item,index) in items" :key="item.id">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item.nama_prd }}</td>
                                                    <td>{{ item.harga_prd}}</td>
                                                    <td>{{ item.stok_prd}}</td>
                                                    <td>
                                                        <v-btn icon color="indigo" light @click="selectProduk(item)">
                                                            <v-icon>mdi-check</v-icon>
                                                        </v-btn>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-data-table>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialogProdukList()">Tutup</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogPerawatanList" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <h5>Pilih Perawatan</h5>
                                 <v-data-table
                                    :headers="headersListPerawatan"
                                    :items="perawatan"
                                    :items-per-page="5"
                                    :loading="isLoading"
                                    :search="searchProduk"
                                    class="elevation-1">
                                        <template v-slot:top>
                                            <v-text-field
                                            append-icon="mdi-magnify"
                                            label="Cari"
                                            v-model="searchProduk"
                                            class="mx-4"
                                            ></v-text-field>
                                        </template>
                                        <template v-slot:body="{ items }">
                                            <tbody>
                                                <tr v-for="(item,index) in items" :key="item.id">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item.nama_prw }}</td>
                                                    <td>{{ item.harga_prw}}</td>
                                                    <td>
                                                        <v-btn icon color="indigo" light @click="selectPerawatan(item)">
                                                            <v-icon>mdi-check</v-icon>
                                                        </v-btn>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-data-table>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialogPerawatanList()">Tutup</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
        
        <v-dialog v-model="dialogProduk" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form :v-model="valid" lazy-validation ref="formProduk">
                            <v-text-field label="Nama Produk" v-model="formProduk.nama_prd" readonly></v-text-field>
                            <v-text-field label="Jumlah" v-model="formProduk.jumlah_prd" type="number" :rules="requiredRule.concat(notMinRule)" @input="setSubTotalProduk()"></v-text-field>
                            <v-text-field label="Sub Total" v-model="formProduk.sub_total_prd" readonly></v-text-field>
                        </v-form>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialogProduk()">Tutup</v-btn>
                        <v-btn color="blue darken-1" text @click="submitProduk()">Selanjutnya</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogPerawatan" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form :v-model="valid" lazy-validation ref="formPerawatan">
                            <v-text-field label="Nama Perawatan" v-model="formPerawatan.nama_prw" readonly></v-text-field>
                            <v-text-field label="Jumlah" v-model="formPerawatan.jumlah_prw" type="number" :rules="requiredRule.concat(notMinRule)" @input="setSubTotalPerawatan()"></v-text-field>
                            <v-text-field label="Sub Total" v-model="formPerawatan.sub_total_prw" readonly></v-text-field>
                        </v-form>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialogPerawatan()">Tutup</v-btn>
                        <v-btn color="blue darken-1" text @click="submitPerawatan()">Selanjutnya</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

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
            urlProduk :this.$apiUrl + "/Produk/",
            urlPerawatan : this.$apiUrl + '/Perawatan/',
            urlDetilProduk : this.$apiUrl + '/DetilTransaksiProduk/',
            urlDetilPerawatan : this.$apiUrl + '/DetilTransaksiPerawatan/',
            urlCust : this.$apiUrl + '/Customer/',
            headersDetilProduk : [
                {text : "No", value : "no"},
                {text : "Nama Produk", value : "id_produk"},
                {text : "Jumlah", value : "jumlah_prd"},
                {text : "Sub Total", value : "sub_total_prd"},
                {text : "Aksi"}
            ],
            headersDetilPerawatan : [
                {text : "No", value : "no"},
                {text : "Nama Perawatan", value : "nama_prw"},
                {text : "Jumlah", value : "jumlah_prw"},
                {text : "Sub Total", value : "sub_total_prw"},
                {text : "Aksi"}
            ],
            headersListProduk : [
                {text : "No", value : "no"},
                {text : "Nama Produk", value : "nama_prd"},
                {text : "Harga", value : "harga_prd"},
                {text : "Stok", value : "stok_prd"},
                {text : "Aksi"}
            ],
            headersListPerawatan : [
                {text : "No", value : "no"},
                {text : "Nama Perawatan", value : "nama_prw"},
                {text : "Harga", value : "harga_prw"},
                {text : "Aksi"}
            ],
            formProduk: {            
                kode_transaksi : '',  
                id_produk : '',
                nama_prd : '',               
                jumlah_prd : '',
                sub_total_prd : '',
                harga_prd : '',
            },
            formPerawatan: {            
                kode_transaksi : '',  
                id_perawatan : '',
                nama_prw : '',               
                jumlah_prw : '',
                sub_total_prw : '',
                harga_prw : '',
            }, 
            searchDetilPerawatan : '',
            searchDetilProduk : '',
            searchPerawatan : '',
            searchProduk : '',
            kode_transaksi : null,
            transaksi : null,
            customer : null,
            produk : [],
            perawatan : [],
            detilProduk : [],
            detilPerawatan : [],
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

        getDataProduk(){
            this.$http.get(this.urlProduk).then(response => {
                    this.produk = response.data.message;
                    this.isLoading = false;
                    this.isEmpty = false;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },
        
        getDataPerawatan(){
            this.$http.get(this.urlPerawatan).then(response => {
                    this.perawatan = response.data.message;
                    this.isLoading = false;
                    this.isEmpty = false;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },
       
        addProduk(){
            this.titleDialog = 'Tambah Produk'
            this.dialogProdukList = true;
        },

        addPerawatan(){
            this.titleDialog = 'Tambah Perawatan'
            this.dialogPerawatanList = true;
        },

        closeDialogProdukList(){
            this.dialogProdukList = false;
        },

        closeDialogPerawatanList(){
            this.dialogPerawatanList = false;
        },

        closeDialogProduk(){
            this.dialogProduk = false;
        },

        closeDialogPerawatan(){
            this.dialogPerawatan = false;
        },

        selectProduk(item){
            this.titleDialog = 'Tambah Jumlah Produk'
            this.dialogProdukList = false;
            this.dialogProduk = true;
            this.formProduk.kode_transaksi = this.kode_transaksi;
            this.formProduk.id_produk = item.id_prd;
            this.formProduk.nama_prd = item.nama_prd;
            this.formProduk.jumlah_prd = 0;
            this.formProduk.sub_total_prd = 0;
            this.formProduk.harga_prd = item.harga_prd;
        },

        setSubTotalProduk(){
            this.formProduk.sub_total_prd = this.formProduk.harga_prd * this.formProduk.jumlah_prd;
        },

        selectPerawatan(item){
            this.titleDialog = 'Tambah Jumlah Perawatan'
            this.dialogPerawatanList = false;
            this.dialogPerawatan = true;
            this.formPerawatan.kode_transaksi = this.kode_transaksi;
            this.formPerawatan.id_perawatan = item.id_prw;
            this.formPerawatan.nama_prw = item.nama_prw;
            this.formPerawatan.jumlah_prw = 0;
            this.formPerawatan.sub_total_prw = 0;
            this.formPerawatan.harga_prw = item.harga_prw;
        },

        setSubTotalPerawatan(){
            this.formPerawatan.sub_total_prw = this.formPerawatan.harga_prw * this.formPerawatan.jumlah_prw;
        },
         
        submitProduk() {
            this.$refs.formProduk.validate();
            if(this.$refs.formProduk.validate()){
                this.setRequestProduk();
                this.isLoading = true;
                if(this.isEdit){
                    this.sendRequestProduk(this.urlDetilProduk+this.updateId);
                }else{
                    this.sendRequestProduk(this.urlDetilProduk);
                }
            }
        },

        sendRequestProduk(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';               
                    this.getDataDetilProduk();
                    this.getDataProduk();
                    this.closeDialogProduk();
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

        setRequestProduk(){
            this.request.append('kode_transaksi', this.formProduk.kode_transaksi);
            this.request.append('id_produk', this.formProduk.id_produk);
            this.request.append('jumlah_prd', this.formProduk.jumlah_prd);
            this.request.append('sub_total_prd', this.formProduk.sub_total_prd);
        },

        editProduk(data){
            this.isEdit = true;
            this.dialogProduk = true;
            this.updateId = data.id_detil_transaksi_produk;
            this.formProduk.kode_transaksi = this.kode_transaksi;
            this.formProduk.id_produk = data.id_produk;
            this.formProduk.nama_prd = data.nama_prd;
            this.formProduk.jumlah_prd = data.jumlah_prd;
            this.formProduk.sub_total_prd = data.sub_total_prd;
            this.formProduk.harga_prd = data.harga_prd;
        },

        deleteProduk(data){
            this.$http.post(this.urlDetilProduk+'delete/'+data.id_detil_transaksi_produk).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';               
                    this.getDataDetilProduk();
                    this.getDataProduk();
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
        submitPerawatan() {
            this.$refs.formPerawatan.validate();
            if(this.$refs.formPerawatan.validate()){
                this.setRequestPerawatan();
                this.isLoading = true;
                if(this.isEdit){
                    this.sendRequestPerawatan(this.urlDetilPerawatan+this.updateId);
                }else{
                    this.sendRequestPerawatan(this.urlDetilPerawatan);
                }
            }
        },

        sendRequestPerawatan(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';               
                    this.getDataDetilPerawatan();
                    this.closeDialogPerawatan();
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

        setRequestPerawatan(){
            this.request.append('kode_transaksi', this.formPerawatan.kode_transaksi);
            this.request.append('id_perawatan', this.formPerawatan.id_perawatan);
            this.request.append('jumlah_prw', this.formPerawatan.jumlah_prw);
            this.request.append('sub_total_prw', this.formPerawatan.sub_total_prw);
        },

        editPerawatan(data){
            this.isEdit = true;
            this.dialogPerawatan = true;
            this.updateId = data.id_detil_transaksi_perawatan;
            this.formPerawatan.kode_transaksi = this.kode_transaksi;
            this.formPerawatan.id_perawatan = data.id_perawatan;
            this.formPerawatan.nama_prw = data.nama_prw;
            this.formPerawatan.jumlah_prw = data.jumlah_prw;
            this.formPerawatan.sub_total_prw = data.sub_total_prw;
            this.formPerawatan.harga_prw = data.harga_prw;
        },

        deletePerawatan(data){
            this.$http.post(this.urlDetilPerawatan+'delete/'+data.id_detil_transaksi_perawatan).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success'; 
                    this.getDataDetilPerawatan();
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
        checkIsMedis(){
            for (let index = 0; index < this.detilPerawatan.length; index++) {
                if(this.detilPerawatan[index].isMedis == 1){
                    return true;
                }
            }
            return false;
        },
        done(){
            if(this.detilProduk.length > 0 || this.checkIsMedis()){
                this.$router.push('/Cs/transaksi/tambah/dokter');
            }else{
                this.$router.push('/Cs/transaksi/tambah/beautician');
            }
            
        }
    },
    mounted(){
        this.kode_transaksi = this.$session.get('kode_transaksi');
        this.getDataTransaksi();
        this.getDataProduk();
        this.getDataPerawatan();
        this.getDataDetilProduk();
        this.getDataDetilPerawatan();
    }
}
</script>