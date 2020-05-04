<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class NotaProdukModel extends CI_Model{
    public function get($no) {
        $query = $this->db->query("SELECT p.nama, p.harga, dt.jumlah, dt.harga * dt.jumlah as 'harga_total', t.tgl_transaksi, t.id_CS as 'pegawai_cs', t.id_cashier as 'pegawai_cashier', t.created_at, t.is_member, t.no_telp FROM detil_transaksi_penjualan dt JOIN transaksi_penjualan t on t.id = dt.id_transaksi JOIN produk p on dt.id_produk = p.id WHERE t.no_transaksi = '$no'");
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