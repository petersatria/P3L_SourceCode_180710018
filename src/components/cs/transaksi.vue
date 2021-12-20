<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Transaksi</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="transaksi"
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
                                    <td>{{ item.kode_transaksi }}</td>
                                    <td>{{item.kode_cust}}</td>
                                    <td>{{ item.total }}</td>
                                    <td>{{ item.tgl_transaksi }}</td>
                                    <td>
                                        <v-btn icon color="error" light @click="deleteData(item)">
                                            <v-icon>mdi-close-thick </v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-data-table>
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
            urlRole :this.$apiUrl + "/Role/",
            headers : [
                {text : "No", value : "no"},
                {text : "Kode Transaksi", value : "kode_transaksi"},
                {text : "Customer", value : "kode_cust"},
                {text : "Total", value : "total"},
                {text : "Tgl Transaksi", value : "tgl_transaksi"},
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
            transaksi : [],
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
            checkBoxMedis : false,
            
        }
    },
    methods : {
        getData(){
            let uri = this.url+'cashier';
            this.$http.get(uri).then(response => {
                    this.transaksi = response.data.message
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
            this.$router.push('/Cs/transaksi/tambah');
        },


        deleteData(data){
            this.$http.post(this.url+'delete/'+data.kode_transaksi).then(response =>{            
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