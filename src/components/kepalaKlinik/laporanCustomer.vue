<template>
    <v-container>
        <v-card class="mt-10 pa-10">
            <v-card-title
            class="font-weight-black d-flex justify-center mb-5 text">Laporan Customer Baru</v-card-title>
            <v-card-text>
                <v-container class="my-10 px-10">
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
                            prepend-icon="mdi-calendar"
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
            window.open(this.$apiUrl+'laporan/customerByYear?tahun='+this.tgl);
        }
    },
}
</script>