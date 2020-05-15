<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class LaporanModel extends CI_Model{
    public function terlaris($tahun,$bulan,$keterangan) {
        $min_bulan = "";
        if ($bulan < 10) {
            $min_bulan = $tahun.'-'.'0'.$bulan.'-'.'01';
        }
        else {
            $min_bulan = $tahun.'-'.$bulan.'-'.'01';
        }
        $max_bulan = date("Y-m-t", strtotime($min_bulan));

        if ($keterangan == 'L') {
            return $this->db->query("SELECT jenis_layanan.nama, SUM(detil_transaksi_layanan.jumlah) AS jumlah FROM detil_transaksi_layanan JOIN jenis_layanan ON detil_transaksi_layanan.id_layanan = jenis_layanan.id JOIN transaksi_layanan ON detil_transaksi_layanan.id_transaksi = transaksi_layanan.id WHERE transaksi_layanan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_layanan.status = 'Lunas' GROUP BY jenis_layanan.nama ORDER BY jumlah DESC LIMIT 1")->result();
        }

        else if ($keterangan = 'P') {
            return $this->db->query("SELECT produk.nama, SUM(detil_transaksi_penjualan.jumlah) AS jumlah FROM detil_transaksi_penjualan JOIN produk ON detil_transaksi_penjualan.id_produk = produk.id JOIN transaksi_penjualan ON detil_transaksi_penjualan.id_transaksi = transaksi_penjualan.id WHERE transaksi_penjualan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_penjualan.status = 'Lunas' GROUP BY produk.nama ORDER BY jumlah DESC LIMIT 1")->result();
        }

        return "ERROR";
    }

    public function pendapatanTahunan($tahun,$bulan) {
        $min_bulan = "";
        if ($bulan < 10) {
            $min_bulan = $tahun.'-'.'0'.$bulan.'-'.'01';
        }
        else {
            $min_bulan = $tahun.'-'.$bulan.'-'.'01';
        }
        $max_bulan = date("Y-m-t", strtotime($min_bulan));

        return $this->db->query("SELECT ((SELECT SUM(detil_transaksi_penjualan.harga*detil_transaksi_penjualan.jumlah) FROM detil_transaksi_penjualan JOIN transaksi_penjualan ON transaksi_penjualan.id = detil_transaksi_penjualan.id_transaksi WHERE transaksi_penjualan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_penjualan.status = 'lunas')-(SELECT SUM(pembayaran_penjualan.diskon) FROM pembayaran_penjualan JOIN transaksi_penjualan ON pembayaran_penjualan.id_transaksi = transaksi_penjualan.id WHERE transaksi_penjualan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan'
        )) AS produk, ((SELECT SUM(detil_transaksi_layanan.harga*detil_transaksi_layanan.jumlah) FROM detil_transaksi_layanan JOIN transaksi_layanan ON transaksi_layanan.id = detil_transaksi_layanan.id_transaksi WHERE transaksi_layanan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_layanan.status = 'lunas')-(SELECT SUM(pembayaran_layanan.diskon) FROM pembayaran_layanan JOIN transaksi_layanan ON pembayaran_layanan.id_transaksi = transaksi_layanan.id WHERE transaksi_layanan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan')) AS layanan")->result();
    }

    public function pendapatanBulanan($tahun,$bulan,$keterangan) {
        $min_bulan = "";
        if ($bulan < 10) {
            $min_bulan = $tahun.'-'.'0'.$bulan.'-'.'01';
        }
        else {
            $min_bulan = $tahun.'-'.$bulan.'-'.'01';
        }
        $max_bulan = date("Y-m-t", strtotime($min_bulan));

        if ($keterangan == 'P') {
            return $this->db->query("SELECT produk.nama AS produk, SUM(detil_transaksi_penjualan.harga*detil_transaksi_penjualan.jumlah) AS harga FROM detil_transaksi_penjualan JOIN transaksi_penjualan ON transaksi_penjualan.id = detil_transaksi_penjualan.id_transaksi JOIN produk ON detil_transaksi_penjualan.id_produk = produk.id WHERE transaksi_penjualan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_penjualan.status = 'lunas' GROUP BY produk.nama")->result();
        }
        else {
            return $this->db->query("SELECT CONCAT(jenis_layanan.nama, ' ', ukuran_hewan.nama) AS layanan, SUM(detil_transaksi_layanan.harga*detil_transaksi_layanan.jumlah) AS harga FROM detil_transaksi_layanan JOIN transaksi_layanan ON transaksi_layanan.id = detil_transaksi_layanan.id_transaksi JOIN layanan ON layanan.id = detil_transaksi_layanan.id_layanan JOIN jenis_layanan ON layanan.id_layanan = jenis_layanan.id JOIN ukuran_hewan ON ukuran_hewan.id = layanan.id_ukuran_hewan WHERE transaksi_layanan.tgl_transaksi BETWEEN '$min_bulan' AND '$max_bulan' AND transaksi_layanan.status = 'lunas' GROUP BY layanan")->result();
        }
    }

    public function pengadaanProdukTahunan($tahun,$bulan) {
        $min_bulan = "";
        if ($bulan < 10) {
            $min_bulan = $tahun.'-'.'0'.$bulan.'-'.'01';
        }
        else {
            $min_bulan = $tahun.'-'.$bulan.'-'.'01';
        }
        $max_bulan = date("Y-m-t", strtotime($min_bulan));

        return $this->db->query("SELECT SUM(produk.harga * detil_pemesanan.jumlah) AS total FROM detil_pemesanan JOIN produk ON produk.id = detil_pemesanan.id_produk JOIN pemesanan ON pemesanan.id = detil_pemesanan.id_pemesanan WHERE pemesanan.tgl_pemesanan BETWEEN '$min_bulan' AND '$max_bulan' AND (pemesanan.status = 'tercetak' OR pemesanan.status = 'diterima')")->result();
    }

    public function pengadaanProdukBulanan($tahun,$bulan) {
        $min_bulan = "";
        if ($bulan < 10) {
            $min_bulan = $tahun.'-'.'0'.$bulan.'-'.'01';
        }
        else {
            $min_bulan = $tahun.'-'.$bulan.'-'.'01';
        }
        $max_bulan = date("Y-m-t", strtotime($min_bulan));

        return $this->db->query("SELECT produk.nama AS produk, SUM(detil_pemesanan.jumlah*produk.harga) AS total FROM detil_pemesanan JOIN produk ON produk.id = detil_pemesanan.id_produk JOIN pemesanan ON pemesanan.id = detil_pemesanan.id_pemesanan WHERE pemesanan.tgl_pemesanan BETWEEN '$min_bulan' AND '$max_bulan' AND (pemesanan.status = 'tercerak' OR pemesanan.status = 'diterima') GROUP BY produk")->result();
    }
}