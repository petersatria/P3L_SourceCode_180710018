<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Data Jadwal</v-card-title>
            <v-card-text>
                <div class="d-flex flex-row-reverse my-5">
                    <v-btn class="red lighten-1" dark @click="addHandler()">Tambah Data</v-btn>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="jadwals"
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
                                    <td>{{item.role_pegawai}}</td>
                                    <td>{{ item.hari }}</td>
                                    <td>{{ item.shift }}</td>
                                    <td>{{ item.jam_mulai }}</td>
                                    <td>{{ item.jam_selesai }}</td>
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
        <v-dialog v-model="dialogPegawai" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <h5>Pilih Pegawai</h5>
                                 <v-data-table
                                    :headers="headersPegawai"
                                    :items="pegawai"
                                    :items-per-page="5"
                                    :loading="isLoading"
                                    :search="searhcPegawai"
                                    class="elevation-1">
                                        <template v-slot:top>
                                            <v-text-field
                                            append-icon="mdi-magnify"
                                            label="Cari"
                                            v-model="searhcPegawai"
                                            class="mx-4"
                                            ></v-text-field>
                                        </template>
                                        <template v-slot:body="{ items }">
                                            <tbody>
                                                <tr v-for="(item,index) in items" :key="item.id">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item.nama_pegawai }}</td>
                                                    <td>{{ item.id_role_pegawai}}</td>
                                                    <td>
                                                        <v-btn icon color="indigo" light @click="selectPegawai(item)">
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
                        <v-btn color="blue darken-1" text @click="closeDialog()">Tutup</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogShift" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title class="justify-center">
                    <span class="headline">{{titleDialog}}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <h5>Pilih Shift</h5>
                                 <v-data-table
                                    :headers="headersShift"
                                    :items="shift"
                                    :items-per-page="5"
                                    :loading="isLoading"
                                    :search="searhcShift"
                                    class="elevation-1">
                                        <template v-slot:top>
                                            <v-text-field
                                            append-icon="mdi-magnify"
                                            label="Cari"
                                            v-model="searhcShift"
                                            class="mx-4"
                                            ></v-text-field>
                                        </template>
                                        <template v-slot:body="{ items }">
                                            <tbody>
                                                <tr v-for="(item,index) in items" :key="item.id">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item.hari }}</td>
                                                    <td>{{ item.shift}}</td>
                                                    <td>{{ item.jam_mulai }}</td>
                                                    <td>{{ item.jam_selesai}}</td>
                                                    <td>
                                                        <v-btn icon color="indigo" light @click="selectShift(item)">
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
                        <v-btn color="blue darken-1" text @click="closeDialog()">Tutup</v-btn>
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
            url : this.$apiUrl + "/DetilJadwal/",
            urlShift :this.$apiUrl + "/Jadwal/",
            urlPegawai :this.$apiUrl + "/Pegawai/",
            headers : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_pegawai"},
                {text : "Role", value : "role_pegawai"},
                {text : "Hari", value : "hari"},
                {text : "Shift", value : "shift"},
                {text : "Jam Mulai", value : "jam_mulai"},
                {text : "Jam Selesai", value : "jam_selesai"},
                {text : "Aksi"}
            ],
            headersPegawai : [
                {text : "No", value : "no"},
                {text : "Nama", value : "nama_pegawai"},
                {text : "Role", value : "id_role_pegawai"},
                {text : "Pilih"}
            ], 
            headersShift : [
                {text : "No", value : "no"},
                {text : "Hari", value : "hari"},
                {text : "Shift", value : "shift"},
                {text : "Jam Mulai", value : "jam_mulai"},
                {text : "Jam Selesai", value : "jam_selesai"},
                {text : "Pilih"}
            ], 
            search : '',
            searhcPegawai : '',
            searhcShift : '',
            jadwals : [],
            shift : [],
            pegawai : [],
            form : {
                id_pegawai : '',
                id_jadwal : '',
            },
            isLoading : true,
            dialogPegawai: false,
            dialogShift: false,
            request : new FormData,
            titleDialog : "Tambah Data Pegawai",
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
                    this.jadwals = response.data.message
                    this.isLoading = false
                    this.isEmpty = false
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataShift(){
            this.$http.get(this.urlShift).then(response => {
                    this.shift = response.data.message;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        getDataPegawai(){
            this.$http.get(this.urlPegawai).then(response => {
                    this.pegawai = response.data.message;
            }).catch(error =>{                   
                this.snackbar = true;               
                this.message = error;               
                this.snackabrColor = 'error';
                this.isEmpty = true;          
            })
        },

        addHandler(){
            this.dialogPegawai = true;
            this.titleDialog = 'Tambah Data Jadwal';
        },

        closeDialog(){
            this.searhcPegawai = '';
            this.searhcShift = '';
            this.dialogPegawai = false;
            this.dialogShift = false;
        },

        selectPegawai(data){
            this.form.id_pegawai = data.id_pegawai;
            this.dialogShift = true;
            this.dialogPegawai = false;
            this.searhcPegawai = '';
        },

        selectShift(data){
            this.form.id_jadwal = data.id_jadwal;
            this.searhcPegawai = '';
            this.submit();
        },
        
        submit() {
            this.setRequest();
            this.isLoading = true;
            if(this.isEdit){
                this.sendRequest(this.url+this.updateId);
            }else{
                this.sendRequest(this.url);
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


        setRequest(){
            this.request.append('id_jadwal', this.form.id_jadwal);
            this.request.append('id_pegawai', this.form.id_pegawai);
        },

        editHandler(data){
            this.isEdit = true;
            this.updateId = data.id_jadwal;
            this.form.id_pegawai = data.id_pegawai;
            this.dialogShift = true;
            this.titleDialog = 'Ubah Data Jadwal'
        },

        deleteData(data){
            this.request.append('id_jadwal', data.id_jadwal);
            this.request.append('id_pegawai', data.id_pegawai);
            this.$http.post(this.url+'delete/',this.request).then(response =>{            
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
        this.getDataShift();
        this.getDataPegawai();
    }
}
</script>