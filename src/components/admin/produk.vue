<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Produk</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="produks"
                    :items-per-page="5"
                    :loading="isLoading"
                    :search="search"
                    class="elevation-1">
                        <template v-slot:top>
                            <v-text-field
                            append-icon="mdi-magnify"
                            label="Cari"
                            v-model="search"
                            class="mx-4"
                            ></v-text-field>
                        </template>
                        <template v-slot:body="{ items }">
                            <tbody>
                                <tr v-for="(item,index) in items" :key="item.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.nama_prd }}</td>
                                    <td>{{ item.deskripsi_prd }}</td>
                                    <td>{{ item.harga_prd }}</td>
                                    <td>{{ item.stok_prd }}</td>
                                    <td>{{ item.ukuran_prd }}</td>
                                    <td>{{ item.satuan_prd }}</td>
                                    <td class="text-center">
                                        <v-btn icon color="indigo" light @click="editHandler(item)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn icon color="error" light @click="deleteData(item)">
                                            <v-icon>mdi-delete</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
            </v-card-text>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-form :v-model="valid" lazy-validation ref="form">
                                    <v-text-field label="Nama" v-model="form.nama_prd"  :rules="requiredRule"></v-text-field>
                                    <v-text-field label="Harga" v-model="form.harga_prd" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>            
                                    <v-text-field label="Stock" v-model="form.stok_prd" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>
                                    <v-text-field label="Ukuran" v-model="form.ukuran_prd" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>
                                    <v-text-field label="Satuan" v-model="form.satuan_prd"  :rules="requiredRule"></v-text-field>
                                    <v-textarea label="Deskripsi" v-model="form.deskripsi_prd" :rules="requiredRule" ></v-textarea>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialog()">Tutup</v-btn>
                        <v-btn color="blue darken-1" text @click="submit()">Simpan</v-btn>
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
            url : this.$apiUrl + "/Produk/",
            headers : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_prd"},
                {text : "Deskripsi", value : "deskripsi_prd"},
                {text : "Harga", value : "harga_prd"},
                {text : "Stock", value : "stok_prd"},
                {text : "Satuan", value : "satuan_prd"},
                {text : "Ukuran", value : "ukuran_prd"},
                {text : "Aksi"}
            ],
             form: {            
                nama_prd : '',  
                deskripsi_prd : '',               
                harga_prd : '',
                stok_prd : '',  
                satuan_prd : '',               
                ukuran_prd : '',
               
            }, 
            search : '',
            produks : [],
            isLoading : true,
            dialog: false,
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            notMinRule : [v => v>0 || 'Data Tidak Boleh Negatif',],
            titleDialog : "Tambah Produk",
            snackbar : false,
            snackabrColor : null,
            message : '',
            isEdit : false,
            updateId : '',
            isEmpty : true,
            
        }
    },
    methods : {
        getData(){
            this.$http.get(this.url).then(response => {
                    this.produks = response.data.message
                    this.isLoading = false
                    this.isEmpty = false;
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
                if(this.isEdit){
                    this.sendRequest(this.url+this.updateId);
                }else{
                    this.sendRequest(this.url);
                }
            }else{
                this.snackbar = true;               
                this.message = 'terdapat kesalahan pada form';               
                this.snackabrColor = 'error';
                this.isEmpty = true;  
            }
        },

        sendRequest(url){
            this.$http.post(url,this.request).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';               
                    this.getData();
                    this.closeDialog();
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
            this.dialog = false;
        },

        setRequest(){
            this.request.append('nama_prd', this.form.nama_prd);
            this.request.append('deskripsi_prd', this.form.deskripsi_prd);
            this.request.append('harga_prd', this.form.harga_prd);
            this.request.append('stok_prd', this.form.stok_prd);
            this.request.append('satuan_prd', this.form.satuan_prd);
            this.request.append('ukuran_prd', this.form.ukuran_prd);
        },

        addHandler(){
            this.isEdit = false;
            this.dialog = true;
            this.titleDialog = 'Tambah Data Produk';
        },

        editHandler(data){
            this.isEdit = true;
            this.form.nama_prd = data.nama_prd;
            this.form.deskripsi_prd = data.deskripsi_prd;
            this.form.harga_prd = data.harga_prd;
            this.form.stok_prd = data.stok_prd;
            this.form.satuan_prd = data.satuan_prd;
            this.form.ukuran_prd = data.ukuran_prd;
            this.updateId = data.id_prd;
            this.dialog = true;
            this.titleDialog = 'Ubah Data Produk'
        },

        deleteData(data){
            this.$http.post(this.url+'delete/'+data.id_prd).then(response =>{            
                this.snackbar = true;
                this.message = response.data.message;
                if(!response.data.error){
                    this.snackabrColor = 'success';               
                    this.getData();
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
        }
    },
    mounted(){
        this.getData()
    }
}
</script>