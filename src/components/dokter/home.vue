<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Daftar Antrian</v-card-title>
            <v-card-text>
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
                                    <td>
                                        <v-btn icon color="indigo" light @click="pilih(item)">
                                            <v-icon>mdi-check</v-icon>
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
            headers : [
                {text : "No", value : "no"},
                {text : "Kode Transaksi", value : "kode_transaksi"},
                {text : "Customer", value : "kode_cust"},
                {text : "Aksi"}
            ],
            search : '',
            transaksi : [],
            isLoading : true,
            snackbar : false,
            snackabrColor : null,
            message : '',
            id_pegawai : '',
            
        }
    },
    methods : {
        getData(){
            let uri = this.url+'dokter/'+this.id_pegawai;
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
        pilih(data){
            this.$session.set('kode_transaksi', data.kode_transaksi);
            this.$router.push('/dokter/pemeriksaan');
        }
    },
    mounted(){
        this.id_pegawai = this.$session.get('pegawai');
        this.getData();
    }
}
</script>