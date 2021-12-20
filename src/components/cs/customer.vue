<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Customer</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="customers"
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
                                    <td>{{ item.nama_cust }}</td>
                                    <td>{{item.alamat_cust}}</td>
                                    <td>{{ item.tgl_lahir_cust }}</td>
                                    <td v-if="item.jk_cust == 'L'"> Laki - Laki </td>
                                    <td v-else> Perempuan </td>
                                    <td>{{ item.no_telp_cust }}</td>
                                    <td>{{ item.email_cust }}</td>
                                    <td>
                                        <v-btn icon color="indigo" light @click="editHandler(item)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn icon color="indigo" light @click="print(item)">
                                            <v-icon>mdi-card-account-details </v-icon>
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
                                    <v-text-field label="Nama" v-model="form.nama_cust" :rules="requiredRule"></v-text-field>
                                    <v-select
                                        v-model="form.jk_cust"
                                        :items="jenisKelamin"
                                        :rules="requiredRule"
                                        label="Jenis Kelamin"
                                        required
                                    ></v-select> 
                                    <v-text-field label="No Telp" v-model="form.no_telp_cust" :rules="requiredRule"></v-text-field>
                                    <v-text-field label="Alamat" v-model="form.alamat_cust" :rules="requiredRule"></v-text-field>
                                     <v-menu
                                        v-model="tglLahir"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="form.tgl_lahir_cust"
                                            label="Tgl Lahir"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="requiredRule"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="form.tgl_lahir_cust"
                                        @input="tglStart = false"
                                        ></v-date-picker>
                                    </v-menu>
                                    <v-text-field label="Email" type="email" v-model="form.email_cust" :rules="requiredRule"></v-text-field>
                                    <v-textarea label="Alergi Obat" v-model="form.alergi_obat_cust" :rules="requiredRule" ></v-textarea>
                                    <v-checkbox
                                        v-model="checkBoxPass"
                                        v-if="isEdit"
                                        label="Ganti Password"
                                    ></v-checkbox>
                                    <v-text-field v-if="checkBoxPass" label="Password" type="password" v-model="form.password_cust" :rules="requiredRule"></v-text-field>
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
            url : this.$apiUrl + "/Customer/",
            headers : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_cust"},
                {text : "Alamat", value : "alamat_cust"},
                {text : "Tgl Lahir", value : "tgl_lahir_cust"},
                {text : "Jenis Kelamin", value : "jk_cust"},
                {text : "No Telp", value : "no_telp_cust"},
                {text : "Email", value : "email_cust"},
                {text : "Aksi"}
            ],
             form: {            
                nama_cust : '',  
                alamat_cust : '',               
                tgl_lahir_cust : '',
                jk_cust : '',
                no_telp_cust : '',
                email_cust : '',
                alergi_obat_cust : '',
                password_cust : '',
            }, 
            search : '',
            customers : [],
            jenisKelamin : ['Laki - Laki', 'Perempuan'],
            isLoading : true,
            dialog: false,
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
            tglLahir : false,
            checkBoxPass : false,
        }
    },
    methods : {
        getData(){
            this.$http.get(this.url).then(response => {
                    this.customers = response.data.message
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
            this.form.password = '';
            this.dialog = false;
        },

        print(item){
            window.open(this.$apiUrl+'/KartuCustomer?kode='+item.kode_cust);
        },

        setRequest(){
            this.request.append('nama_cust', this.form.nama_cust);
            this.request.append('alamat_cust', this.form.alamat_cust);
            this.request.append('tgl_lahir_cust', this.form.tgl_lahir_cust);
            this.request.append('no_telp_cust', this.form.no_telp_cust);
            this.request.append('email_cust', this.form.email_cust);
            this.request.append('alergi_obat_cust', this.form.alergi_obat_cust);
            this.request.append('password_cust',this.form.password_cust);

            if(this.form.jk_pegawai == 'Laki - Laki'){
                this.request.append('jk_cust','L');
            }else{
                this.request.append('jk_cust','P');
            }
        },

        addHandler(){
            this.isEdit = false;
            this.dialog = true;
            this.titleDialog = 'Tambah Data Customer';
        },

        editHandler(data){
            this.isEdit = true;
            this.form.nama_cust = data.nama_cust;
            this.form.tgl_lahir_cust = data.tgl_lahir_cust;
            this.form.no_telp_cust = data.no_telp_cust;
            this.form.email_cust = data.email_cust;
            this.form.alamat_cust = data.alamat_cust;
            this.form.alergi_obat_cust = data.alergi_obat_cust;
            if(data.jk_cust == 'L'){
                this.form.jk_cust = 'Laki - Laki';
            }else{
                this.form.jk_cust = 'Perempuan';
            }   
            this.updateId = data.kode_cust;
            this.dialog = true;
            this.titleDialog = 'Ubah Data Customer'
        },

        deleteData(data){
            this.$http.post(this.url+'delete/'+data.id_pegawai).then(response =>{            
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
        this.getData();
    }
}
</script>