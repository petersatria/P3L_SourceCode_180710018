<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class HewanModel extends CI_Model
{
    private $table = 'hewan';
    public $id;
    public $nama;
    public $id_jenis_hewan;
    public $tanggal_lahir;
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
            'field' => 'id_jenis_hewan',
            'label' => 'id_jenis_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'tanggal_lahir',
            'label' => 'tanggal_lahir',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,nama,id_jenis_hewan,tanggal_lahir')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama,id_jenis_hewan,tanggal_lahir')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id,nama,id_jenis_hewan,tanggal_lahir')->from($this->table)->where(array('id'=>$request,))->get()->row();
    }
    
    public function searchByString($request){
        return $this->db->select('id,nama,id_jenis_hewan,tanggal_lahir')->from($this->table)->where(array('isDelete'=>0))->like('nama',$request)->or_like('nama',$request,'before')->or_like('nama',$request,'after')->get()->result();
    }

    public function store($request) {
        $this->nama = $request->nama;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->created_by = $request->created_by;
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
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama,
            'id_jenis_hewan' => $this->id_jenis_hewan,
            'tanggal_lahir' => $this->tanggal_lahir,
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