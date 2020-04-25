<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilPemesananModel extends CI_Model
{
    private $table = 'detil_pemesanan';
    public $id;
    public $id_pemesanan;
    public $id_produk;
    public $jumlah;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_pemesanan',
            'label' => 'id_pemesanan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($id_pemesanan) { 
        return $this->db->select('id,id_pemesanan,id_produk,jumlah')->from($this->table)->where(array('id_pemesanan'=> $id_pemesanan))->get()->result();
    }

    public function search($id) { 
        return $this->db->select('id,id_pemesanan,id_produk,jumlah')->from($this->table)->where(array('id'=> $id))->get()->result();
    }

    public function store($request) {
        $this->id_produk = $request->id_produk;
        $this->id_pemesanan = $request->id_pemesanan;
        $this->jumlah = $request->jumlah;
        $this->created_by = $request->created_by;
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            return $this->updatePemesanan($this->created_at,$this->created_by);
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
            return $this->updatePemesanan($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function delete($request){
        $this->id = $request->id;
        $this->id_pemesanan = $this->getIdPemesanan();
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->db->where(array('id' => $this->id))->delete($this->table)){
            return $this->updatePemesanan($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updatePemesanan($updated_at,$updated_by){
        $data = array( 
            'updated_by'      => $updated_by, 
            'updated_at'      => $updated_at
        );
        if($this->db->where(array('id' => $this->id_pemesanan))->update('pemesanan', $data)){
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

    public function getJumlahById($id){
        return $this->db->select('jumlah')->from($this->table)->where(array('id' => $id))->get()->row()->jumlah;
    }

    public function getIdPemesanan(){
        return $this->db->select('id_pemesanan')->from($this->table)->where(array('id' => $this->id))->get()->row()->id_pemesanan;
    }

    public function getId($id_pemesanan,$id_produk){
        $response = $this->db->select('id')->from($this->table)->where(array('id_produk' => $id_produk, 'id_pemesanan' => $id_pemesanan) )->get()->row();
        if($response != null){
            return $response->id;
        }
        return null;
    }
    
}