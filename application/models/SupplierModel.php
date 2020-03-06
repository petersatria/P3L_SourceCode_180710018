<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class SupplierModel extends CI_Model
{
    private $table = 'supplier';
    public $id;
    public $nama;
    public $no_telp;
    public $alamat;
    public $kota;
    public $isDelete;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp',
            'label' => 'no_telp',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat',
            'label' => 'alamat',
            'rules' => 'required'
        ],
        [
            'field' => 'kota',
            'label' => 'kota',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,nama,no_telp,alamat,kota')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama,no_telp,alamat,kota')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }
    
    public function searchForeign($request){
        return $this->db->select('id,nama')->from($this->table)->where(array('id'=>$request,))->get()->row();
    }
    
    public function store($request) {
        $this->nama = $request->nama;
        $this->no_telp = $request->no_telp;
        $this->alamat = $request->alamat;
        $this->kota = $request->kota;
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
        $this->nama = $request->nama;
        $this->no_telp = $request->no_telp;
        $this->alamat = $request->alamat;
        $this->kota = $request->kota;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama, 
            'no_telp'      => $this->no_telp, 
            'alamat'      => $this->alamat, 
            'kota'      => $this->kota, 
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

    private function getIdPegawai($username){
        $request = $this->db->select('id')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }
}