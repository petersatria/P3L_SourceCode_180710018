<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class LogModel extends CI_Model
{
    public function hewanGet(){
        return $this->db->query("SELECT * FROM(SELECT h.nama ,'dibuat' as keterangan,h.created_at AS Time, pg.nama AS pegawai from hewan h JOIN pegawai pg on h.created_by = pg.id UNION ALL SELECT h.nama, IF(h.isDelete = 0, 'diubah', 'dihapus') as keterangan, h.updated_at AS Time, pg.nama as pegawai FROM hewan h JOIN pegawai pg ON h.updated_by = pg.id) dum order by time desc")->result();
    }

    public function hewanSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT h.nama ,'dibuat' as keterangan,h.created_at AS Time, pg.nama AS pegawai from hewan h JOIN pegawai pg on h.created_by = pg.id UNION ALL SELECT h.nama, IF(h.isDelete = 0, 'diubah', 'dihapus') as keterangan, h.updated_at AS Time, pg.nama as pegawai FROM hewan h JOIN pegawai pg ON h.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function hewanSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT h.nama ,'dibuat' as keterangan,h.created_at AS Time, pg.nama AS pegawai from hewan h JOIN pegawai pg on h.created_by = pg.id UNION ALL SELECT h.nama, IF(h.isDelete = 0, 'diubah', 'dihapus') as keterangan, h.updated_at AS Time, pg.nama as pegawai FROM hewan h JOIN pegawai pg ON h.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function hewanSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT h.nama ,'dibuat' as keterangan,h.created_at AS Time, pg.nama AS pegawai from hewan h JOIN pegawai pg on h.created_by = pg.id UNION ALL SELECT h.nama, IF(h.isDelete = 0, 'diubah', 'dihapus') as keterangan, h.updated_at AS Time, pg.nama as pegawai FROM hewan h JOIN pegawai pg ON h.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function jenisHewanGet(){
        return $this->db->query("SELECT * FROM(SELECT jh.keterangan as nama,'dibuat' as keterangan,jh.created_at AS Time, pg.nama AS pegawai from jenis_hewan jh JOIN pegawai pg on jh.created_by = pg.id UNION ALL SELECT jh.keterangan as nama, IF(jh.isDelete = 0, 'diubah', 'dihapus') as keterangan, jh.updated_at AS Time, pg.nama as pegawai FROM jenis_hewan jh JOIN pegawai pg ON jh.updated_by = pg.id) dum order by time desc")->result();
    }

    public function jenisHewanSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT jh.keterangan as nama ,'dibuat' as keterangan,jh.created_at AS Time, pg.nama AS pegawai from jenis_hewan jh JOIN pegawai pg on jh.created_by = pg.id UNION ALL SELECT jh.keterangan as nama, IF(jh.isDelete = 0, 'diubah', 'dihapus') as keterangan, jh.updated_at AS Time, pg.nama as pegawai FROM jenis_hewan jh JOIN pegawai pg ON jh.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function jenisHewanSearchByName($keterangan){
        return $this->db->query("SELECT * FROM(SELECT jh.keterangan as nama ,'dibuat' as keterangan,jh.created_at AS Time, pg.nama AS pegawai from jenis_hewan jh JOIN pegawai pg on jh.created_by = pg.id UNION ALL SELECT jh.keterangan as nama, IF(jh.isDelete = 0, 'diubah', 'dihapus') as keterangan, jh.updated_at AS Time, pg.nama as pegawai FROM jenis_hewan jh JOIN pegawai pg ON jh.updated_by = pg.id) dum where nama like '$keterangan' order by time desc ")->result();
    }

    public function jenisHewanSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT jh.keterangan as nama ,'dibuat' as keterangan,jh.created_at AS Time, pg.nama AS pegawai from jenis_hewan jh JOIN pegawai pg on jh.created_by = pg.id UNION ALL SELECT jh.keterangan as nama, IF(jh.isDelete = 0, 'diubah', 'dihapus') as keterangan, jh.updated_at AS Time, pg.nama as pegawai FROM jenis_hewan jh JOIN pegawai pg ON jh.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function jenisLayananGet(){
        return $this->db->query("SELECT * FROM(SELECT jl.nama ,'dibuat' as keterangan,jl.created_at AS Time, pg.nama AS pegawai from jenis_layanan jl JOIN pegawai pg on jl.created_by = pg.id UNION ALL SELECT jl.nama, IF(jl.isDelete = 0, 'diubah', 'dihapus') as keterangan, jl.updated_at AS Time, pg.nama as pegawai FROM jenis_layanan jl JOIN pegawai pg ON jl.updated_by = pg.id) dum order by time desc")->result();
    }

    public function jenisLayananSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT jl.nama ,'dibuat' as keterangan,jl.created_at AS Time, pg.nama AS pegawai from jenis_layanan jl JOIN pegawai pg on jl.created_by = pg.id UNION ALL SELECT jl.nama, IF(jl.isDelete = 0, 'diubah', 'dihapus') as keterangan, jl.updated_at AS Time, pg.nama as pegawai FROM jenis_layanan jl JOIN pegawai pg ON jl.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function jenisLayananSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT jl.nama ,'dibuat' as keterangan,jl.created_at AS Time, pg.nama AS pegawai from jenis_layanan jl JOIN pegawai pg on jl.created_by = pg.id UNION ALL SELECT jl.nama, IF(jl.isDelete = 0, 'diubah', 'dihapus') as keterangan, jl.updated_at AS Time, pg.nama as pegawai FROM jenis_layanan jl JOIN pegawai pg ON jl.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function jenisLayananSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT jl.nama ,'dibuat' as keterangan,jl.created_at AS Time, pg.nama AS pegawai from jenis_layanan jl JOIN pegawai pg on jl.created_by = pg.id UNION ALL SELECT jl.nama, IF(jl.isDelete = 0, 'diubah', 'dihapus') as keterangan, jl.updated_at AS Time, pg.nama as pegawai FROM jenis_layanan jl JOIN pegawai pg ON jl.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function kategoriProdukGet(){
        return $this->db->query("SELECT * FROM(SELECT kp.keterangan as nama,'dibuat' as keterangan,kp.created_at AS Time, pg.nama AS pegawai from kategori_produk kp JOIN pegawai pg on kp.created_by = pg.id UNION ALL SELECT kp.keterangan as nama, IF(kp.isDelete = 0, 'diubah', 'dihapus') as keterangan, kp.updated_at AS Time, pg.nama as pegawai FROM kategori_produk kp JOIN pegawai pg ON kp.updated_by = pg.id) dum order by time desc")->result();
    }

    public function kategoriProdukSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT kp.keterangan as nama ,'dibuat' as keterangan,kp.created_at AS Time, pg.nama AS pegawai from kategori_produk kp JOIN pegawai pg on kp.created_by = pg.id UNION ALL SELECT kp.keterangan as nama, IF(kp.isDelete = 0, 'diubah', 'dihapus') as keterangan, kp.updated_at AS Time, pg.nama as pegawai FROM kategori_produk kp JOIN pegawai pg ON kp.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function kategoriProdukSearchByName($keterangan){
        return $this->db->query("SELECT * FROM(SELECT kp.keterangan as nama ,'dibuat' as keterangan,kp.created_at AS Time, pg.nama AS pegawai from kategori_produk kp JOIN pegawai pg on kp.created_by = pg.id UNION ALL SELECT kp.keterangan as nama, IF(kp.isDelete = 0, 'diubah', 'dihapus') as keterangan, kp.updated_at AS Time, pg.nama as pegawai FROM kategori_produk kp JOIN pegawai pg ON kp.updated_by = pg.id) dum where nama like '$keterangan' order by time desc ")->result();
    }

    public function kategoriProdukSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT kp.keterangan as nama ,'dibuat' as keterangan,kp.created_at AS Time, pg.nama AS pegawai from kategori_produk kp JOIN pegawai pg on kp.created_by = pg.id UNION ALL SELECT kp.keterangan as nama, IF(kp.isDelete = 0, 'diubah', 'dihapus') as keterangan, kp.updated_at AS Time, pg.nama as pegawai FROM kategori_produk kp JOIN pegawai pg ON kp.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function memberGet(){
        return $this->db->query("SELECT * FROM(SELECT m.nama ,'dibuat' as keterangan,m.created_at AS Time, pg.nama AS pegawai from member m JOIN pegawai pg on m.created_by = pg.id UNION ALL SELECT m.nama, IF(m.isDelete = 0, 'diubah', 'dihapus') as keterangan, m.updated_at AS Time, pg.nama as pegawai FROM member m JOIN pegawai pg ON m.updated_by = pg.id) dum order by time desc")->result();
    }

    public function memberSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT m.nama ,'dibuat' as keterangan,m.created_at AS Time, pg.nama AS pegawai from member m JOIN pegawai pg on m.created_by = pg.id UNION ALL SELECT m.nama, IF(m.isDelete = 0, 'diubah', 'dihapus') as keterangan, m.updated_at AS Time, pg.nama as pegawai FROM member m JOIN pegawai pg ON m.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function memberSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT m.nama ,'dibuat' as keterangan,m.created_at AS Time, pg.nama AS pegawai from member m JOIN pegawai pg on m.created_by = pg.id UNION ALL SELECT m.nama, IF(m.isDelete = 0, 'diubah', 'dihapus') as keterangan, m.updated_at AS Time, pg.nama as pegawai FROM member m JOIN pegawai pg ON m.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function memberSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT m.nama ,'dibuat' as keterangan,m.created_at AS Time, pg.nama AS pegawai from member m JOIN pegawai pg on m.created_by = pg.id UNION ALL SELECT m.nama, IF(m.isDelete = 0, 'diubah', 'dihapus') as keterangan, m.updated_at AS Time, pg.nama as pegawai FROM member m JOIN pegawai pg ON m.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function pegawaiGet(){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pegawai p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pegawai p JOIN pegawai pg ON p.updated_by = pg.id) dum order by time desc")->result();
    }

    public function pegawaiSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pegawai p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pegawai p JOIN pegawai pg ON p.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function pegawaiSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pegawai p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pegawai p JOIN pegawai pg ON p.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function pegawaiSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pegawai p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pegawai p JOIN pegawai pg ON p.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     public function produkGet(){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from produk p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM produk p JOIN pegawai pg ON p.updated_by = pg.id) dum order by time desc")->result();
    }

    public function produkSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from produk p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM produk p JOIN pegawai pg ON p.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function produkSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from produk p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM produk p JOIN pegawai pg ON p.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function produkSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT p.nama ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from produk p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.nama, IF(p.isDelete = 0, 'diubah', 'dihapus') as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM produk p JOIN pegawai pg ON p.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function rolePegawaiGet(){
        return $this->db->query("SELECT * FROM(SELECT rp.keterangan as nama,'dibuat' as keterangan,rp.created_at AS Time, pg.nama AS pegawai from role_pegawai rp JOIN pegawai pg on rp.created_by = pg.id UNION ALL SELECT rp.keterangan as nama, IF(rp.isDelete = 0, 'diubah', 'dihapus') as keterangan, rp.updated_at AS Time, pg.nama as pegawai FROM role_pegawai rp JOIN pegawai pg ON rp.updated_by = pg.id) dum order by time desc")->result();
    }

    public function rolePegawaiSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT rp.keterangan as nama ,'dibuat' as keterangan,rp.created_at AS Time, pg.nama AS pegawai from role_pegawai rp JOIN pegawai pg on rp.created_by = pg.id UNION ALL SELECT rp.keterangan as nama, IF(rp.isDelete = 0, 'diubah', 'dihapus') as keterangan, rp.updated_at AS Time, pg.nama as pegawai FROM role_pegawai rp JOIN pegawai pg ON rp.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function rolePegawaiSearchByName($keterangan){
        return $this->db->query("SELECT * FROM(SELECT rp.keterangan as nama ,'dibuat' as keterangan,rp.created_at AS Time, pg.nama AS pegawai from role_pegawai rp JOIN pegawai pg on rp.created_by = pg.id UNION ALL SELECT rp.keterangan as nama, IF(rp.isDelete = 0, 'diubah', 'dihapus') as keterangan, rp.updated_at AS Time, pg.nama as pegawai FROM role_pegawai rp JOIN pegawai pg ON rp.updated_by = pg.id) dum where nama like '$keterangan' order by time desc ")->result();
    }

    public function rolePegawaiSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT rp.keterangan as nama ,'dibuat' as keterangan,rp.created_at AS Time, pg.nama AS pegawai from role_pegawai rp JOIN pegawai pg on rp.created_by = pg.id UNION ALL SELECT rp.keterangan as nama, IF(rp.isDelete = 0, 'diubah', 'dihapus') as keterangan, rp.updated_at AS Time, pg.nama as pegawai FROM role_pegawai rp JOIN pegawai pg ON rp.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function supplierGet(){
        return $this->db->query("SELECT * FROM(SELECT s.nama ,'dibuat' as keterangan,s.created_at AS Time, pg.nama AS pegawai from supplier s JOIN pegawai pg on s.created_by = pg.id UNION ALL SELECT s.nama, IF(s.isDelete = 0, 'diubah', 'dihapus') as keterangan, s.updated_at AS Time, pg.nama as pegawai FROM supplier s JOIN pegawai pg ON s.updated_by = pg.id) dum order by time desc")->result();
    }

    public function supplierSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT s.nama ,'dibuat' as keterangan,s.created_at AS Time, pg.nama AS pegawai from supplier s JOIN pegawai pg on s.created_by = pg.id UNION ALL SELECT s.nama, IF(s.isDelete = 0, 'diubah', 'dihapus') as keterangan, s.updated_at AS Time, pg.nama as pegawai FROM supplier s JOIN pegawai pg ON s.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function supplierSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT s.nama ,'dibuat' as keterangan,s.created_at AS Time, pg.nama AS pegawai from supplier s JOIN pegawai pg on s.created_by = pg.id UNION ALL SELECT s.nama, IF(s.isDelete = 0, 'diubah', 'dihapus') as keterangan, s.updated_at AS Time, pg.nama as pegawai FROM supplier s JOIN pegawai pg ON s.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function supplierSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT s.nama ,'dibuat' as keterangan,s.created_at AS Time, pg.nama AS pegawai from supplier s JOIN pegawai pg on s.created_by = pg.id UNION ALL SELECT s.nama, IF(s.isDelete = 0, 'diubah', 'dihapus') as keterangan, s.updated_at AS Time, pg.nama as pegawai FROM supplier s JOIN pegawai pg ON s.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function ukuranHewanGet(){
        return $this->db->query("SELECT * FROM(SELECT uh.nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from ukuran_hewan uh JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT uh.nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM ukuran_hewan uh JOIN pegawai pg ON uh.updated_by = pg.id) dum order by time desc")->result();
    }

    public function ukuranHewanSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT uh.nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from ukuran_hewan uh JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT uh.nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM ukuran_hewan uh JOIN pegawai pg ON uh.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function ukuranHewanSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT uh.nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from ukuran_hewan uh JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT uh.nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM ukuran_hewan uh JOIN pegawai pg ON uh.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function ukuranHewanSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT uh.nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from ukuran_hewan uh JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT uh.nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM ukuran_hewan uh JOIN pegawai pg ON uh.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function pemesananGet(){
        return $this->db->query("SELECT * FROM(SELECT p.no_PO ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pemesanan p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.no_PO, 'diubah' as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pemesanan p JOIN pegawai pg ON p.updated_by = pg.id) dum order by time desc")->result();
    }

    public function pemesananSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT p.no_PO ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pemesanan p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.no_PO, 'diubah' as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pemesanan p JOIN pegawai pg ON p.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function pemesananSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT p.no_PO ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pemesanan p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.no_PO, 'diubah' as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pemesanan p JOIN pegawai pg ON p.updated_by = pg.id) dum where no_PO like '$nama' order by time desc ")->result();
    }

    public function pemesananSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT p.no_PO ,'dibuat' as keterangan,p.created_at AS Time, pg.nama AS pegawai from pemesanan p JOIN pegawai pg on p.created_by = pg.id UNION ALL SELECT p.no_PO, 'diubah' as keterangan, p.updated_at AS Time, pg.nama as pegawai FROM pemesanan p JOIN pegawai pg ON p.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function transaksiLayananGet(){
        return $this->db->query("SELECT * FROM(SELECT tl.no_transaksi ,'dibuat' as keterangan,tl.created_at AS Time, pg.nama AS pegawai from transaksi_layanan tl JOIN pegawai pg on tl.created_by = pg.id UNION ALL SELECT tl.no_transaksi, 'diubah' as keterangan, tl.updated_at AS Time, pg.nama as pegawai FROM transaksi_layanan tl JOIN pegawai pg ON tl.updated_by = pg.id) dum order by time desc")->result();
    }

    public function transaksiLayananSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT tl.no_transaksi ,'dibuat' as keterangan,tl.created_at AS Time, pg.nama AS pegawai from transaksi_layanan tl JOIN pegawai pg on tl.created_by = pg.id UNION ALL SELECT tl.no_transaksi, 'diubah' as keterangan, tl.updated_at AS Time, pg.nama as pegawai FROM transaksi_layanan tl JOIN pegawai pg ON tl.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function transaksiLayananSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT tl.no_transaksi ,'dibuat' as keterangan,tl.created_at AS Time, pg.nama AS pegawai from transaksi_layanan tl JOIN pegawai pg on tl.created_by = pg.id UNION ALL SELECT tl.no_transaksi, 'diubah' as keterangan, tl.updated_at AS Time, pg.nama as pegawai FROM transaksi_layanan tl JOIN pegawai pg ON tl.updated_by = pg.id) dum where no_transaksi like '$nama' order by time desc ")->result();
    }

    public function transaksiLayananSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT tl.no_transaksi ,'dibuat' as keterangan,tl.created_at AS Time, pg.nama AS pegawai from transaksi_layanan tl JOIN pegawai pg on tl.created_by = pg.id UNION ALL SELECT tl.no_transaksi, 'diubah' as keterangan, tl.updated_at AS Time, pg.nama as pegawai FROM transaksi_layanan tl JOIN pegawai pg ON tl.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function transaksiPenjualanGet(){
        return $this->db->query("SELECT * FROM(SELECT tp.no_transaksi ,'dibuat' as keterangan,tp.created_at AS Time, pg.nama AS pegawai from transaksi_penjualan tp JOIN pegawai pg on tp.created_by = pg.id UNION ALL SELECT tp.no_transaksi, 'diubah' as keterangan, tp.updated_at AS Time, pg.nama as pegawai FROM transaksi_penjualan tp JOIN pegawai pg ON tp.updated_by = pg.id) dum order by time desc")->result();
    }

    public function transaksiPenjualanSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT tp.no_transaksi ,'dibuat' as keterangan,tp.created_at AS Time, pg.nama AS pegawai from transaksi_penjualan tp JOIN pegawai pg on tp.created_by = pg.id UNION ALL SELECT tp.no_transaksi, 'diubah' as keterangan, tp.updated_at AS Time, pg.nama as pegawai FROM transaksi_penjualan tp JOIN pegawai pg ON tp.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function transaksiPenjualanSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT tp.no_transaksi ,'dibuat' as keterangan,tp.created_at AS Time, pg.nama AS pegawai from transaksi_penjualan tp JOIN pegawai pg on tp.created_by = pg.id UNION ALL SELECT tp.no_transaksi, 'diubah' as keterangan, tp.updated_at AS Time, pg.nama as pegawai FROM transaksi_penjualan tp JOIN pegawai pg ON tp.updated_by = pg.id) dum where no_transaksi like '$nama' order by time desc ")->result();
    }

    public function transaksiPenjualanSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT tp.no_transaksi ,'dibuat' as keterangan,tp.created_at AS Time, pg.nama AS pegawai from transaksi_penjualan tp JOIN pegawai pg on tp.created_by = pg.id UNION ALL SELECT tp.no_transaksi, 'diubah' as keterangan, tp.updated_at AS Time, pg.nama as pegawai FROM transaksi_penjualan tp JOIN pegawai pg ON tp.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function layananGet(){
        return $this->db->query("SELECT * FROM(SELECT concat(jl.nama,' ', uh.nama ) as nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT concat(jl.nama,' ', uh.nama ) as nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg ON uh.updated_by = pg.id) dum order by time desc")->result();
    }

    public function layananSearchByPegawai($pegawai){
        return $this->db->query("SELECT * FROM(SELECT concat(jl.nama,' ', uh.nama ) as nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT concat(jl.nama,' ', uh.nama ) as nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg ON uh.updated_by = pg.id) dum where pegawai like '$pegawai' order by time desc ")->result();
    }

    public function layananSearchByName($nama){
        return $this->db->query("SELECT * FROM(SELECT concat(jl.nama,' ', uh.nama ) as nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT concat(jl.nama,' ', uh.nama ) as nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg ON uh.updated_by = pg.id) dum where nama like '$nama' order by time desc ")->result();
    }

    public function layananSearchByTime($time){
        return $this->db->query("SELECT * FROM(SELECT concat(jl.nama,' ', uh.nama ) as nama ,'dibuat' as keterangan,uh.created_at AS Time, pg.nama AS pegawai from layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg on uh.created_by = pg.id UNION ALL SELECT concat(jl.nama,' ', uh.nama ) as nama, IF(uh.isDelete = 0, 'diubah', 'dihapus') as keterangan, uh.updated_at AS Time, pg.nama as pegawai FROM layanan l join jenis_layanan jl on l.id_layanan = jl.id join ukuran_hewan uh on uh.id = l.id_ukuran_hewan JOIN pegawai pg ON uh.updated_by = pg.id) dum where time like '$time' order by time desc ")->result();
    }
    
}