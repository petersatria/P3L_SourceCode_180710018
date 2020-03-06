<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PegawaiModel extends CI_Model
{
    private $table = 'pegawai';
    public $id;
    public $id_role_pegawai;
    public $nama;
    public $tanggal_lahir;
    public $alamat;
    public $no_telp;
    public $username;
    public $password;
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
            'field' => 'id_role_pegawai',
            'label' => 'id_role_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'tanggal_lahir',
            'label' => 'tanggal_lahir',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat',
            'label' => 'alamat',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp',
            'label' => 'no_telp',
            'rules' => 'required'
        ],
        [
            'field' => 'username',
            'label' => 'username',
            'rules' => 'required'
        ],
        [
            'field' => 'password',
            'label' => 'password',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,nama,id_role_pegawai,tanggal_lahir,alamat,username,no_telp')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama,id_role_pegawai,tanggal_lahir,alamat,username,no_telp,password')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id,nama,id_role_pegawai,tanggal_lahir,alamat,username,no_telp,password')->from($this->table)->where(array('id'=>$request))->get()->row();
    }
    
    public function store($request) {
        $this->nama = $request->nama;
        $this->id_role_pegawai = $request->id_role_pegawai;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->alamat = $request->alamat;
        $this->username = $request->username;
        $this->password = $request->password;
        $this->no_telp = $request->no_telp;
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
        $this->id_role_pegawai = $request->id_role_pegawai;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->alamat = $request->alamat;
        $this->username = $request->username;
        $this->password = $request->password;
        $this->no_telp = $request->no_telp;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama, 
            'id_role_pegawai'      => $this->id_role_pegawai, 
            'tanggal_lahir'      => $this->tanggal_lahir, 
            'alamat'      => $this->alamat, 
            'username'      => $this->username,
            'password'      => $this->password, 
            'no_telp'      => $this->no_telp, 
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

    public function getIdPegawai($username){
        $request = $this->db->select('id')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }

    public function login($request){
        $request = $this->db->select('username,password')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }

    public function getByUsername($request){
        $request = $this->db->select('id,username')->from('pegawai')->where(array('username' => $request))->limit(1)->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }
}