<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Promo</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="promos"
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
                                    <td>{{ item.kode_promo }}</td>
                                    <td>{{ item.nama_promo }}</td>
                                    <td>{{ item.diskon * 100 }}</td>
                                    <td>{{ item.tgl_promo_start }}</td>
                                    <td>{{ item.tgl_promo_end }}</td>
                                    <td v-if="item.status == 1">Aktif</td>
                                    <td v-else>Tidak Aktif</td>
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
                                    <v-text-field label="Kode" v-model="form.kode_promo"  :rules="requiredRule" :disabled="isEdit"></v-text-field>
                                    <v-text-field label="Nama" v-model="form.nama_promo" :rules="requiredRule"></v-text-field>            
                                    <v-text-field label="Diskon (%)" v-model="form.diskon" type="number" :rules="requiredRule.concat(notMinRule)"></v-text-field>
                                    <v-menu
                                        v-model="tglStart"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="form.tgl_promo_start"
                                            label="Tgl Promo Dimulai"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="requiredRule"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="form.tgl_promo_start"
                                        @input="tglStart = false"
                                        ></v-date-picker>
                                    </v-menu>
                                    <v-menu
                                        v-model="tglEnd"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="form.tgl_promo_end"
                                            label="Tgl Promo Selesai"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="requiredRule"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="form.tgl_promo_end"
                                        @input="tglEnd = false"
                                        ></v-date-picker>
                                    </v-menu>
                                    <v-checkbox
                                        v-model="checkBoxStatus"
                                        label="Aktif"
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
            url : this.$apiUrl + "/Promo/",
            headers : [
                {text : "No", value : "no"},
                {text : "Kode", value : "kode_promo"},
                {text : "Nama", value : "nama_promo"},
                {text : "Diskon(%)", value : "diskon"},
                {text : "Tgl Mulai", value : "tgl_promo_start"},
                {text : "Tgl Selesai", value : "tgl_promo_end"},
                {text : "Status", value : "status"},
                {text : "Aksi"}
            ],
             form: {            
                kode_promo : '',               
                nama_promo : '',
                diskon : '',  
                tgl_promo_start : '',               
                tgl_promo_end : '',
                status : '',
               
            }, 
            tglStart : false,
            tglEnd : false,
            search : '',
            promos : [],
            isLoading : true,
            dialog: false,
            request : new FormData,
            valid : true,
            requiredRule: [v => !!v || 'Data Tidak Boleh Kosong',],
            notMinRule : [v => v>=0 && v<=100 || 'Data Tidak Boleh Negatif dan Lebih dari 100',],
            titleDialog : "Tambah Promo",
            snackbar : false,
            snackabrColor : null,
            message : '',
            isEdit : false,
            updateId : '',
            isEmpty : true,
            checkBoxStatus : false,
            
        }
    },
    methods : {
        getData(){
            this.$http.get(this.url).then(response => {
                    this.promos = response.data.message
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
            this.request.append('kode_promo', this.form.kode_promo);
            this.request.append('nama_promo', this.form.nama_promo);
            this.request.append('diskon', (this.form.diskon / 100));
            this.request.append('tgl_promo_start', this.form.tgl_promo_start);
            this.request.append('tgl_promo_end', this.form.tgl_promo_end)
            if(this.checkBoxStatus){
                this.request.append('status', '1');
            }else{
                this.request.append('status', '0');
            }
        },

        addHandler(){
            this.isEdit = false;
            this.dialog = true;
            this.titleDialog = 'Tambah Data Promo';
        },

        editHandler(data){
            this.isEdit = true;
            this.form.kode_promo = data.kode_promo;
            this.form.nama_promo = data.nama_promo;
            this.form.diskon = data.diskon*100;
            this.form.tgl_promo_start = data.tgl_promo_start;
            this.form.tgl_promo_end = data.tgl_promo_end;
            this.updateId = data.kode_promo;
            if(data.status == '1'){
                this.checkBoxStatus = true;
            }else{
                this.checkBoxStatus = false;
            }   
            this.dialog = true;
            this.titleDialog = 'Ubah Data Promo'
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