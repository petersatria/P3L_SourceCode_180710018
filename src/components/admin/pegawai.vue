<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Pegawai</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="pegawai"
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
                                    <td>{{ item.nama_pegawai }}</td>
                                    <td>{{item.id_role_pegawai}}</td>
                                    <td>{{ item.no_telp_pegawai }}</td>
                                    <td v-if="item.jk_pegawai == 'L'"> Laki - Laki </td>
                                    <td v-else> Perempuan </td>
                                    <td>{{ item.alamat_pegawai }}</td>
                                    <td>{{ item.username }}</td>
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
                                    <v-text-field label="Nama" v-model="form.nama_pegawai" :rules="requiredRule"></v-text-field>
                                    <v-select
                                        v-model="form.jk_pegawai"
                                        :items="jenisKelamin"
                                        :rules="requiredRule"
                                        label="Jenis Kelamin"
                                        required
                                    ></v-select> 
                                    <v-text-field label="No Telp" v-model="form.no_telp_pegawai" :rules="requiredRule"></v-text-field>
                                    <v-text-field label="Alamat" v-model="form.alamat_pegawai" :rules="requiredRule"></v-text-field>
                                    <v-select
                                        v-model="form.id_role_pegawai"
                                        :items="rolesNama"
                                        :rules="requiredRule"
                                        label="Role"
                                        required
                                    ></v-select>        
                                    <v-text-field label="Username" v-model="form.username" :rules="requiredRule"></v-text-field>

                                    
                                    <v-checkbox
                                        v-model="checkBoxPass"
                                        v-if="isEdit"
                                        label="Ganti Password"
                                    ></v-checkbox>

                                    <v-text-field v-if="!isEdit || checkBoxPass" label="Password" :type="showPassword ? 'text' : 'password'" v-model="form.password" :rules="requiredRule" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword"></v-text-field>
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
            url : this.$apiUrl + "/Pegawai/",
            urlRole :this.$apiUrl + "/Role/",
            headers : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_pegawai"},
                {text : "Role", value : "id_role_pegawai"},
                {text : "No Telp", value : "no_telp_pegawai"},
                {text : "Jenis Kelamin", value : "jk_pegawai"},
                {text : "Alamat", value : "alamat_pegawai"},
                {text : "Username", value : "username"},
                {text : "Aksi"}
            ],
             form: {            
                nama_pegawai : '',  
                id_role_pegawai : '',               
                no_telp_pegawai : '',
                jk_pegawai : '',
                alamat_pegawai : '',
                username : '',
                password : '',
            }, 
            search : '',
            pegawai : [],
            roles : [],
            rolesNama : [],
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
            checkBoxPass : false,
            
        }
    },
    methods : {
        getData(){
            this.$http.get(this.url).then(response => {
                    this.pegawai = response.data.message
                    this.isLoading = false
                    this.isEmpty = false;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataRole(){
            this.$http.get(this.urlRole).then(response => {
                    this.roles = response.data.message;
                    this.rolesNama = response.data.message.map(x=>x.nama_role);
                    this.isLoading = false;
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
            this.form.password = '';
            this.dialog = false;
        },

        setRequest(){
            this.request.append('nama_pegawai', this.form.nama_pegawai);
            this.request.append('no_telp_pegawai', this.form.no_telp_pegawai);
            this.request.append('alamat_pegawai', this.form.alamat_pegawai);
            this.request.append('username', this.form.username);
            this.request.append('password', this.form.password);
            for(var i = 0; i < this.roles.length; i++) {
                if (this.roles.map(x => x.nama_role)[i] === this.form.id_role_pegawai) {
                    this.request.append('id_role_pegawai',this.roles.map(x => x.id_role)[i]);
                }
            }

            if(this.form.jk_pegawai == 'Laki - Laki'){
                this.request.append('jk_pegawai','L');
            }else{
                this.request.append('jk_pegawai','P');
            }
        },

        addHandler(){
            this.isEdit = false;
            this.dialog = true;
            this.titleDialog = 'Tambah Data Pegawai';
        },

        editHandler(data){
            this.isEdit = true;
            this.form.nama_pegawai = data.nama_pegawai;
            this.form.no_telp_pegawai = data.no_telp_pegawai;
            this.form.alamat_pegawai = data.alamat_pegawai;
            this.form.username = data.username;
            this.form.id_role_pegawai = data.id_role_pegawai;
            if(data.jk_pegawai == 'L'){
                this.form.jk_pegawai = 'Laki - Laki';
            }else{
                this.form.jk_pegawai = 'Perempuan';
            }   
            this.updateId = data.id_pegawai;
            this.dialog = true;
            this.titleDialog = 'Ubah Data Pegawai'
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
        this.getDataRole();
    }
}
</script>