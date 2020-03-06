<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class LayananModel extends CI_Model
{
    private $table = 'layanan';
    public $id;
    public $harga;
    public $id_ukuran_hewan;
    public $id_layanan;
    public $isDelete;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'id_ukuran_hewan',
            'label' => 'id_ukuran_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,harga,id_ukuran_hewan,id_layanan')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,harga,id_ukuran_hewan,id_layanan')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->result();
    }
    public function store($request) {
        $this->harga = $request->harga;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->created_by = $request->created_by;
        $this->created_at = date('Y-m-d H:i:s');
        $this->isDelete = 0;
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
        $this->id = $request->id;
        $this->harga = $request->harga;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'harga'      => $this->harga, 
            'id_ukuran_hewan'      => $this->id_ukuran_hewan, 
            'id_layanan'      => $this->id_layanan,
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
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
        $this->id = $request->id;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'isDelete'      => 1, 
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
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