<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class MemberModel extends CI_Model
{
    private $table = 'member';
    public $id;
    public $nama;
    public $no_telp;
    public $tanggal;
    public $alamat;
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
            'field' => 'tanggal',
            'label' => 'tanggal',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat',
            'label' => 'alamat',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }
    
    public function get() { 
        return $this->db->select('id,nama,no_telp,tanggal,alamat')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama,no_telp,tanggal,alamat')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id,nama,no_telp,tanggal,alamat')->from($this->table)->where(array('id'=>$request))->get()->row();
    }
    
    public function searchByString($request){
        return $this->db->select('id,nama,no_telp,tanggal,alamat')->from($this->table)->where(array('isDelete'=>0))->like('nama',$request)->or_like('nama',$request,'before')->or_like('nama',$request,'after')->get()->result();
    }

    public function store($request) {
        $this->nama = $request->nama;
        $this->no_telp = $request->no_telp;
        $this->tanggal = $request->tanggal;
        $this->alamat = $request->alamat;
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
        $this->tanggal = $request->tanggal;
        $this->alamat = $request->alamat;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama, 
            'no_telp'      => $this->no_telp, 
            'tanggal'      => $this->tanggal, 
            'alamat'      => $this->alamat,
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

    public function getIdMember($created_at, $craeted_by){
        $request = $this->db->select('id')->from($this->table)->where(array('created_by' => $created_by, 'created_at' => $created_at))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }
    
}