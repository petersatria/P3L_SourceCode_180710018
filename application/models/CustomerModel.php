<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class CustomerModel extends CI_Model
{
    public function get_produk($order_by, $isDesc) { 
        $desc = "";
        if ($isDesc == "TRUE") {
            $desc = "DESC";
        }
        return $this->db->query("SELECT produk.nama, kategori_produk.keterangan AS kategori, produk.harga, produk.jmlh AS jumlah, produk.link_gambar FROM produk JOIN kategori_produk ON produk.id_kategori_produk = kategori_produk.id WHERE produk.isDelete = 0 ORDER BY ".$order_by." ".$desc)->result();
    }

    public function search_produk($id) {
        return $this->db->query("SELECT produk.nama, kategori_produk.keterangan AS kategori, produk.harga, produk.jmlh AS jumlah, produk.link_gambar FROM produk JOIN kategori_produk ON produk.id_kategori_produk = kategori_produk.id WHERE produk.id = $id")->result();
    }

    public function get_layanan($order_by, $isDesc) {
        $desc = "";
        if ($isDesc == "TRUE") {
            $desc = "DESC";
        }
        return $this->db->query("SELECT CONCAT(jenis_layanan.nama,' ',ukuran_hewan.nama) AS nama, layanan.harga, layanan.url_gambar FROM layanan JOIN jenis_layanan ON jenis_layanan.id = layanan.id_layanan JOIN ukuran_hewan ON ukuran_hewan.id = layanan.id_ukuran_hewan WHERE layanan.isDelete = 0 ORDER BY ".$order_by." ".$desc)->result();
    }

    public function search_layanan($id) {
        return $this->db->query("SELECT CONCAT(jenis_layanan.nama,' ',ukuran_hewan.nama) AS nama, layanan.harga, layanan.url_gambar FROM layanan JOIN jenis_layanan ON jenis_layanan.id = layanan.id_layanan JOIN ukuran_hewan ON ukuran_hewan.id = layanan.id_ukuran_hewan WHERE layanan.id = $id")->result();
    }
}