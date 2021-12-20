import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const Home = () => import(/* webpackChunkName: "dashboardAdmin" */ '../views/Home.vue')
const AdminDashboard = () => import(/* webpackChunkName: "dashboardAdmin" */ '../views/dashboardAdmin.vue')
const KepalaKlinik = () => import(/* webpackChunkName: "dashboarKepalaKlinik" */ '../views/dashboardKepala.vue')
const CustomerService = () => import(/* webpackChunkName: "dashboardCustomerService" */ '../views/dashboardCS.vue')
const Dokter = () => import(/* webpackChunkName: "dashboardDokter" */ '../views/dashboardDokter.vue')
const Kasir = () => import(/* webpackChunkName: "dashboardKasir" */ '../views/dashboardKasir.vue')
const Beautician = () => import(/* webpackChunkName: "dashboardBeautician" */ '../views/dashboardBeautician.vue')

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/admin',
    name: 'Admin',
    component: AdminDashboard,
    children : [
      {
        path : '/admin/produk',
        component : () => import('../components/admin/produk.vue')
      },
      {
        path : '/admin/perawatan',
        component : () => import('../components/admin/perawatan.vue')
      },
      {
        path : '/admin/pegawai',
        component : () => import('../components/admin/pegawai.vue')
      },
    ]
  },
  {
    path: '/kepalaKlinik',
    name: 'KepalaKlinik',
    component: KepalaKlinik,
    children : [
      {
        path : '/kepalaKlinik/jadwal',
        component : () => import('../components/kepalaKlinik/detilJadwal.vue')
      },
      {
        path : '/kepalaKlinik/promo',
        component : () => import('../components/kepalaKlinik/promo.vue')
      },
      {
        path : '/kepalaKlinik/laporan/customer',
        component : () => import('../components/kepalaKlinik/laporanCustomer.vue')
      },
      {
        path : '/kepalaKlinik/laporan/pendapatan',
        component : () => import('../components/kepalaKlinik/laporanPendapatan.vue')
      },
      {
        path : '/kepalaKlinik/laporan/produk',
        component : () => import('../components/kepalaKlinik/laporanProduk.vue')
      },
      {
        path : '/kepalaKlinik/laporan/perawatan',
        component : () => import('../components/kepalaKlinik/laporanPerawatan.vue')
      }
    ]
  },
  {
    path: '/Cs',
    name: 'CustomerService',
    component: CustomerService,
    children : [
      {
        path : '/Cs/customer',
        component : () => import('../components/cs/customer.vue')
      },
      {
        path : '/Cs/transaksi',
        component : () => import('../components/cs/transaksi.vue')
      },
      {
        path : '/Cs/transaksi/tambah',
        component : () => import('../components/cs/tambahTransaksi.vue')
      },
      {
        path : '/Cs/transaksi/tambah/detil',
        component : () => import('../components/cs/tambahDetil.vue')
      },
      {
        path : '/Cs/transaksi/tambah/dokter',
        component : () => import('../components/cs/tambahDokter.vue')
      },
      {
        path : '/Cs/transaksi/tambah/beautician',
        component : () => import('../components/cs/tambahBeautician.vue')
      },
    ]
  },
  {
    path: '/dokter',
    name: 'dokter',
    component: Dokter,
    children : [
      {
        path : '/dokter',
        component : () => import('../components/dokter/home.vue')
      },
      {
        path : '/dokter/pemeriksaan',
        component : () => import('../components/dokter/pemeriksaan.vue')
      },
      {
        path : '/dokter/pemeriksaan/beautician',
        component : () => import('../components/dokter/tambahBeautician.vue')
      },
    ]
  },
  {
    path: '/kasir',
    name: 'kasir',
    component: Kasir,
    children : [
      {
        path : '/kasir',
        component : () => import('../components/kasir/home.vue')
      },
      {
        path : '/kasir/pembayaran',
        component : () => import('../components/kasir/pembayaran.vue')
      },
    ]
  },
  {
    path: '/beautician',
    name: 'beautician',
    component: Beautician,
    children : [
      {
        path : '/beautician',
        component : () => import('../components/beautician/home.vue')
      },
    ]
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
