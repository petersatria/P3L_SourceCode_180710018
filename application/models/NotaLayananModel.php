<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class NotaLayananModel extends CI_Model{
    public function get($no) {
        $query = $this->db->query("SELECT CONCAT(jenis_layanan.nama, ' ',ukuran_hewan.nama) AS 'nama_layanan', layanan.harga, detil_transaksi_layanan.jumlah, detil_transaksi_layanan.harga AS 'harga_total', transaksi_layanan.created_at, transaksi_layanan.id_cashier, transaksi_layanan.id_CS, transaksi_layanan.is_member, transaksi_layanan.no_telp FROM detil_transaksi_layanan JOIN transaksi_layanan ON detil_transaksi_layanan.id_transaksi = transaksi_layanan.id JOIN layanan ON detil_transaksi_layanan.id_layanan = layanan.id JOIN jenis_layanan ON layanan.id_layanan = jenis_layanan.id JOIN ukuran_hewan ON layanan.id_ukuran_hewan = ukuran_hewan.id WHERE transaksi_layanan.no_transaksi = '$no'");
        return $query->result();
    }

    public function get_pegawai($id_pegawai) {
        $query = $this->db->query("SELECT nama FROM pegawai WHERE id = $id_pegawai");
        return $query->result();
    }

    public function get_member($no_telp) {
        $query = $this->db->query("SELECT nama FROM member WHERE no_telp = $no_telp");
        return $query->result();
    }
}