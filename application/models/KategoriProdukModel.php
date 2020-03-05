<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class KategoriProdukModel extends CI_Model
{
    private $table = 'kategori_produk';
    public $id;
    public $keterangan;
    public $isDelete;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'keterangan',
            'label' => 'keterangan',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,keterangan')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,keterangan')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->result();
    }
    public function store($request) {
        $this->keterangan = $request->keterangan;
        $this->created_by = $this->getIdPegawai($request->created_by);
        $this->isDelete = 0;
        $this->created_at = date('Y-m-d H:i:s');
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
        $this->keterangan = $request->keterangan;
        $this->updated_by = $this->getIdPegawai($request->updated_by);
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'keterangan'      => $this->keterangan, 
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
        $this->updated_by = $this->getIdPegawai($request->updated_by);
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

    private function getIdPegawai($username){
        $request = $this->db->select('id')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }
}