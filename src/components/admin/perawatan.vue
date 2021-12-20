<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Perawatan</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="perawatans"
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
                                    <td>{{ item.nama_prw }}</td>
                                    <td>{{ item.deskripsi_prw }}</td>
                                    <td>{{ item.harga_prw }}</td>
                                    <td>{{ item.poin_prw }}</td>
                                    <td v-if="item.isMedis == 1"> Ya </td>
                                    <td v-else> Tidak </td>
                                    <td>
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
                                    <v-text-field label="Nama" v-model="form.nama_prw" :rules="requiredRule"></v-text-field>
                                    <v-text-field label="Harga" v-model="form.harga_prw" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>            
                                    <v-text-field label="Poin" v-model="form.poin_prw" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>
                                    <v-textarea label="Deskripsi" v-model="form.deskripsi_prw" :rules="requiredRule" ></v-textarea>
                                    <v-checkbox
                                        v-model="checkBoxMedis"
                                        label="Perawatan Medis"
                                    ></v-checkbox>
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
            url : this.$apiUrl + "/Perawatan/",
            headers : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_prw"},
                {text : "Deskripsi", value : "deskripsi_prw"},
                {text : "Harga", value : "harga_prw"},
                {text : "Poin", value : "poin_prw"},
                {text : "Medis", value : "isMedis"},
                {text : "Aksi"}
            ],
             form: {            
                nama_prw : '',  
                deskripsi_prw : '',               
                harga_prw : '',
                poin_prw : '',  
                isMedis : '',
               
            }, 
            search : '',
            perawatans : [],
            isLoading : true,
            dialog: false,
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            notMinRule : [v => v>0 || 'Data Tidak Boleh Negatif',],
            titleDialog : "Tambah Perawatan",
            snackbar : false,
            snackabrColor : null,
            message : '',
            isEdit : false,
            updateId : '',
            isEmpty : true,
            checkBoxMedis : false,
            
        }
    },
    methods : {
        getData(){
            this.$http.get(this.url).then(response => {
                    this.perawatans = response.data.message
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
            this.request.append('nama_prw', this.form.nama_prw);
            this.request.append('deskripsi_prw', this.form.deskripsi_prw);
            this.request.append('harga_prw', this.form.harga_prw);
            this.request.append('poin_prw', this.form.poin_prw);
            if(this.checkBoxMedis){
                this.request.append('isMedis', '1');
            }else{
                this.request.append('isMedis', '0');
            }
        },

        addHandler(){
            this.isEdit = false;
            this.dialog = true;
            this.titleDialog = 'Tambah Data Perawatan';
        },

        editHandler(data){
            this.isEdit = true;
            this.form.nama_prw = data.nama_prw;
            this.form.deskripsi_prw = data.deskripsi_prw;
            this.form.harga_prw = data.harga_prw;
            this.form.poin_prw = data.poin_prw;
            if(data.isMedis == '1'){
                this.checkBoxMedis = true;
            }else{
                this.checkBoxMedis = false;
            }   
            this.updateId = data.id_prw;
            this.dialog = true;
            this.titleDialog = 'Ubah Data Perawatan'
        },

        deleteData(data){
            this.$http.post(this.url+'delete/'+data.id_prw).then(response =>{            
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