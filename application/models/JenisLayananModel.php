<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class JenisLayananModel extends CI_Model
{
    private $table = 'jenis_layanan';
    public $id;
    public $nama;
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
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() {
        return $this->db->select('id,nama')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }
    public function store($request) {
        $this->nama = $request->nama;
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
        $this->nama = $request->nama;
        $this->updated_by = $this->getIdPegawai($request->updated_by);
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama, 
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