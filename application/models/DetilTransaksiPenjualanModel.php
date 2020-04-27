<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiPenjualanModel extends CI_Model
{
    private $table = 'detil_transaksi_penjualan';
    public $id;
    public $id_produk;
    public $id_transaksi;
    public $harga;
    public $jumlah;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'id_transaksi',
            'label' => 'id_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($id_transaksi) { 
        return $this->db->select('dt.id,dt.id_transaksi,dt.id_produk,dt.jumlah,dt.harga,(dt.jumlah * dt.harga) as subtotal, p.link_gambar')->from('detil_transaksi_penjualan dt')->join('produk p','p.id = dt.id_produk')->where(array('dt.id_transaksi'=> $id_transaksi))->get()->result();
    }

    public function search($id) { 
        return $this->db->select('id,id_transaksi,id_produk,jumlah,harga,(jumlah * harga) as subtotal')->from($this->table)->where(array('id'=> $id))->get()->result();
    }

    public function store($request) {
        $this->id_produk = $request->id_produk;
        $this->id_transaksi = $request->id_transaksi;
        $this->harga = $request->harga;
        $this->jumlah = $request->jumlah;
        $this->created_by = $request->created_by;
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            return $this->updateTransaksi($this->created_at,$this->created_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function update($request) {
        $this->id = $request->id;
        $this->jumlah = $request->jumlah;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'jumlah'      => $this->jumlah, 
            'updated_by'      => $this->updated_by, 
            'updated_at'      => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
            return $this->updateTransaksi($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function delete($request){
        $this->id = $request->id;
        $this->id_transaksi = $this->getIdTransaksi();
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->db->where(array('id' => $this->id))->delete($this->table)){
            return $this->updateTransaksi($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateTransaksi($updated_at,$updated_by){
        $data = array( 
            'updated_by'      => $updated_by, 
            'updated_at'      => $updated_at
        );
        if($this->db->where(array('id' => $this->id_transaksi))->update('transaksi_penjualan', $data)){
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
    
    public function getJumlahDibeli($id_produk){
        return $this->db->select('IFNULL(sum(dt.jumlah),0) as total')->from('detil_transaksi_penjualan dt')->join('transaksi_penjualan tp','tp.id = dt.id_transaksi')->where(array('dt.id_produk'=>$id_produk))->like('tp.status','belum lunas')->get()->row()->total;
    }

    public function getJumlahById($id){
        return $this->db->select('jumlah')->from($this->table)->where(array('id' => $id))->get()->row()->jumlah;
    }

    public function getIdTransaksi(){
        return $this->db->select('id_transaksi')->from($this->table)->where(array('id' => $this->id))->get()->row()->id_transaksi;
    }

    public function getId($id_transaksi,$id_produk){
        $response = $this->db->select('id')->from($this->table)->where(array('id_produk' => $id_produk, 'id_transaksi' => $id_transaksi) )->get()->row();
        if($response != null){
            return $response->id;
        }
        return null;
    }

    public function getJumlah($id_transaksi,$id_produk){
        $response = $this->db->select('jumlah')->from($this->table)->where(array('id_produk' => $id_produk, 'id_transaksi' => $id_transaksi) )->get()->row();
        if($response != null){
            return $response->jumlah;
        }
        return 0;
    }
}