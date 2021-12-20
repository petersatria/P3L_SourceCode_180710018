<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Laporan Perawatan Terlaris</v-card-title>
            <v-card-text>
                <v-container class="my-10 px-10">
                    <v-select
                        v-model="bulan"
                        :items="bulans"
                        :rules="requiredRule"
                        label="Bulan"
                        required
                    ></v-select>
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="true"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="tgl"
                            label="Tahun"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-date-picker
                         reactive
                        show-current
                        ref="picker"
                        v-model="date"
                        :max="nowDate"
                        min="2018"
                        ></v-date-picker>
                    </v-menu> 
                    <div class="d-flex flex-row-reverse my-5">
                        <v-btn color="blue darken-1" text @click="submit()">Selanjutnya</v-btn>
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
            tgl : "2021",
            bulans : ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
            bulan : null,
            menu : false,
            snackbar : false,
            snackabrColor : null,
            message : '',
            nowDate: new Date().toISOString().slice(0,10),
            
            
        }
    },
    watch: {
        menu (val) {
            val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
        }
    },
    computed: {
        date: {
            get: function () {
                return this.$date;
            },
            set: function (newValue) {
                this.tgl =  newValue.slice(0, -6);
            },
        }
    },
    methods : {       
        submit(){
            for (let index = 0; index < this.bulans.length; index++) {
             if(this.bulans[index] == this.bulan){
                 window.open(this.$apiUrl+'laporan/perawatanLaris?bulan='+(index+1)+'&tahun='+this.tgl);
                }
            }
            
        }
    },
}
</script>