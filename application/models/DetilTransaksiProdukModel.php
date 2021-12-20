<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiProdukModel extends CI_Model
{
    private $table = 'detil_transaksi_produk';
    public $id_detil_transaksi_produk;
    public $kode_transaksi;
    public $id_produk;
    public $jumlah_prd;
    public $sub_total_prd;
    public $rule = [
        [
            'field' => 'kode_transaksi',
            'label' => 'kode_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah_prd',
            'label' => 'jumlah_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'sub_total_prd',
            'label' => 'sub_total_prd',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($request) { 
        return $this->db->select('dtp.id_detil_transaksi_produk as id_detil_transaksi_produk, dtp.id_produk as id_produk, dtp.kode_transaksi as kode_transaksi, dtp.jumlah_prd as jumlah_prd, dtp.sub_total_prd as sub_total_prd, p.nama_prd as nama_prd, p.harga_prd as harga_prd')->from('detil_transaksi_produk dtp')->join('produk p','p.id_prd = dtp.id_produk')->where(array('kode_transaksi'=>$request))->get()->result();
    }

    public function search($request) { 
        return $this->db->select('*')->from($this->table)->where(array('id_detil_transaksi_produk'=>$request))->get()->row();
    }

    public function getTotalLaporan($request){
        return $this->db->query('SELECT p.nama_prd as nama_prd, IFNULL(total.total,0) as total, p.harga_prd as harga_prd, concat(p.ukuran_prd,concat(" ",p.satuan_prd)) as ukuran_prd FROM produk p LEFT JOIN (SELECT dtp.id_produk as id_produk, sum(dtp.jumlah_prd) as total FROM detil_transaksi_produk dtp JOIN transaksi t on t.kode_transaksi = dtp.kode_transaksi WHERE date_format(t.tgl_transaksi,"%m-%Y") = "'.$request.'" AND t.status_transaksi = "done" GROUP BY dtp.id_produk) as total ON p.id_prd = total.id_produk ORDER BY total desc LIMIT 10')->result();
    }

    public function getTotalLaporanMobile ($request){
        return $this->db->query('SELECT p.nama_prd as nama_prd, IFNULL(total.total,0) as total, p.harga_prd as harga_prd, p.ukuran_prd as ukuran_prd, p.satuan_prd as satuan_prd, p.stok_prd as stok_prd FROM produk p LEFT JOIN (SELECT dtp.id_produk as id_produk, sum(dtp.jumlah_prd) as total FROM detil_transaksi_produk dtp JOIN transaksi t on t.kode_transaksi = dtp.kode_transaksi WHERE date_format(t.tgl_transaksi,"%m-%Y") = "'.$request.'" AND t.status_transaksi = "done" GROUP BY dtp.id_produk) as total ON p.id_prd = total.id_produk ORDER BY total desc LIMIT 10')->result();
    }


    public function getId($request){
        $request = $this->db->select('*')->from($this->table)->where(array('kode_transaksi'=>$request->kode_transaksi,'id_produk'=>$request->id_produk))->get()->row();
        if($request!=null){
            return $request->id_detil_transaksi_produk;
        }
        return null;
    }

    public function store($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->id_produk = $request->id_produk;
        $this->jumlah_prd = $request->jumlah_prd;
        $this->sub_total_prd = $request->sub_total_prd;
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

    public function update($request) {
        $this->id_detil_transaksi_produk = $request->id_detil_transaksi_produk;
        $this->jumlah_prd = $request->jumlah_prd;
        $this->sub_total_prd = $request->sub_total_prd;
        $data = array( 
            'jumlah_prd'      => $this->jumlah_prd,
            'sub_total_prd'      => $this->sub_total_prd,
        );
        if($this->db->where(array('id_detil_transaksi_produk' => $this->id_detil_transaksi_produk))->update($this->table, $data)){
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

    public function delete($request){
        if($this->db->where(array('id_detil_transaksi_produk'=>$request))->delete($this->table)){
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