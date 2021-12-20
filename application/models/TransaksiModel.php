<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class TransaksiModel extends CI_Model
{
    private $table = 'transaksi';
    public $kode_transaksi;
    public $kode_cust;
    public $id_jadwal;
    public $kode_promo;
    public $tgl_transaksi;
    public $total;
    public $keluhan;
    public $status_transaksi;
    public $rule = [
        [
            'field' => 'kode_cust',
            'label' => 'kode_cust',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('*')->from($this->table)->get()->result();
    }

    public function getMobile($request) { 
        return $this->db->select('*')->from($this->table)->where(array('kode_cust'=>$request,'status_transaksi'=>'done'))->order_by("tgl_transaksi", "desc")->get()->result();
    }

    public function getByStatus($request) { 
        return $this->db->select('*')->from($this->table)->where(array('status_transaksi'=>$request))->get()->result();
    }

    public function getPendapatanByYear($request){
        return $this->db->query('SELECT bulans.bulan as bulan, IFNULL(total.total_pendapatan_produk, 0) as total_pendatapan_produk, IFNULL(total.total_pendapatan_perawatan,0) as total_pendapatan_perawatan FROM (SELECT "January" as bulan 
        UNION SELECT "February" as bulan 
        UNION SELECT "March" as bulan 
        UNION SELECT "April" as bulan 
        UNION SELECT "May" as bulan 
        UNION SELECT "June" as bulan 
        UNION SELECT "July" as bulan 
        UNION SELECT "August" as bulan 
        UNION SELECT "September" as bulan 
        UNION SELECT "October" as bulan 
        UNION SELECT "November" as bulan 
        UNION SELECT "December" as bulan) as bulans 
        LEFT JOIN (SELECT detil_produk.bulan as bulan, detil_produk.total_pendapatan_produk as total_pendapatan_produk, detil_perawatan.total_pendapatan_perawatan as total_pendapatan_perawatan FROM 
        (SELECT date_format(t.tgl_transaksi, "%M") as bulan, sum(dtprd.sub_total_prd) as total_pendapatan_produk FROM transaksi as t JOIN detil_transaksi_produk as dtprd ON dtprd.kode_transaksi = t.kode_transaksi WHERE date_format(t.tgl_transaksi,"%Y") = "'.$request.'" AND t.status_transaksi = "done") as detil_produk LEFT JOIN (SELECT date_format(t.tgl_transaksi, "%M") as bulan, SUM(dtprw.sub_total_prw) as total_pendapatan_perawatan FROM transaksi as t JOIN detil_transaksi_perawatan as dtprw ON dtprw.kode_transaksi = t.kode_transaksi WHERE date_format(t.tgl_transaksi,"%Y") = "'.$request.'" AND t.status_transaksi = "done") as detil_perawatan on detil_perawatan.bulan = detil_produk.bulan) as total ON bulans.bulan = total.bulan')->result();
    }

    public function search($request){
        return $this->db->select('*')->from($this->table)->where(array('kode_transaksi'=>$request))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('*')->from($this->table)->where(array('kode_transaksi'=>$request,))->get()->row();
    }

    public function getCount(){
        $request = $this->db->select('count(*) as total')->from($this->table)->get()->row();
        if($request != null){
            return $request->total+1;
        }
        return null;
    }

    public function getByDokter($request){
        return $this->db->select('t.kode_transaksi as kode_transaksi, t.kode_cust as kode_cust ')->from('transaksi t')->join('penanggung_jawab pj', 'pj.kode_transaksi = t.kode_transaksi')->join('pegawai p','p.id_pegawai = pj.id_pegawai')->where(array('t.status_transaksi'=>'doctor','p.id_pegawai'=>$request))->get()->result();
    }

    public function getKeluhanCust($request){
        return $this->db->select('t.kode_transaksi as kode_transaksi, t.tgl_transaksi as tgl_transaksi, t.keluhan as keluhan')->from('transaksi t')->join('customer c', 'c.kode_cust = t.kode_cust')->where(array('t.status_transaksi'=>'done','t.kode_cust'=>$request))->get()->result();
    }

    public function getByBeautician($request){
        return $this->db->select('t.kode_transaksi as kode_transaksi, t.kode_cust as kode_cust ')->from('transaksi t')->join('penanggung_jawab pj', 'pj.kode_transaksi = t.kode_transaksi')->where(array('t.status_transaksi'=>'beautician','pj.id_pegawai'=>$request))->get()->row();
    }

    public function store($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->kode_cust = $request->kode_cust;
        $this->id_jadwal = $request->id_jadwal;
        $this->tgl_transaksi = $request->tgl_transaksi;
        $this->status_transaksi = 'cashier';
        $this->total = 0;
        if($this->db->insert($this->table, $this)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function delete($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->status_transaksi = 'cancel';
        $data = array( 
            'status_transaksi'      => $this->status_transaksi,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function promo($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->kode_promo = $request->kode_promo;
        $this->total = $request->total;
        $data = array( 
            'kode_promo'      => $this->kode_promo,
            'total'      => $this->total,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateTotal($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->total = $request->total;
        $data = array( 
            'total'      => $this->total,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }
    
    public function updateDoctor($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->status_transaksi = 'doctor';
        $data = array( 
            'status_transaksi'      => $this->status_transaksi,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateKeluhan($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->keluhan = $request->keluhan;
        $data = array( 
            'keluhan'      => $this->keluhan,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateCashier($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->status_transaksi = 'cashier';
        $data = array( 
            'status_transaksi'      => $this->status_transaksi,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateBeautician($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->status_transaksi = 'beautician';
        $data = array( 
            'status_transaksi'      => $this->status_transaksi,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateSelesai($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->status_transaksi = 'done';
        $data = array( 
            'status_transaksi'      => $this->status_transaksi,
        );
        if($this->db->where(array('kode_transaksi' => $this->kode_transaksi))->update($this->table, $data)){
            return [
                'msg'=>'Berhasil',
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }
}