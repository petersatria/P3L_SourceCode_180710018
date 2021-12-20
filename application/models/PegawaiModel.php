<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PegawaiModel extends CI_Model
{
    private $table = 'pegawai';
    public $id_pegawai;
    public $id_role_pegawai;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $no_telp_pegawai;
    public $jk_pegawai;
    public $username;
    public $password;
    public $isAvailable;
    public $rule = [
        [
            'field' => 'id_role_pegawai',
            'label' => 'id_role_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_pegawai',
            'label' => 'nama_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat_pegawai',
            'label' => 'alamat_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp_pegawai',
            'label' => 'no_telp_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'jk_pegawai',
            'label' => 'jk_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'username',
            'label' => 'username',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id_pegawai,id_role_pegawai,nama_pegawai,alamat_pegawai, no_telp_pegawai ,jk_pegawai,username,password,isAvailable')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id_pegawai,id_role_pegawai,nama_pegawai,alamat_pegawai, no_telp_pegawai ,jk_pegawai,username,password,isAvailable')->from($this->table)->where(array('id_pegawai'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id_pegawai,id_role_pegawai,nama_pegawai,alamat_pegawai, no_telp_pegawai ,jk_pegawai,username,password,isAvailable')->from($this->table)->where(array('id_pegawai'=>$request,))->get()->row();
    }

    public function searchByString($request){
        return $this->db->select('id_pegawai,id_role_pegawai,nama_pegawai, no_telp_pegawai ,jk_pegawai,username,password,isAvailable')->from($this->table)->where(array('isDelete'=>0))->like('nama_pegawai',$request)->or_like('nama_pegawai',$request,'before')->or_like('nama_pegawai',$request,'after')->get()->result();
    }

    public function login($username){
        $request = $this->db->select('id_pegawai,username,password,id_role_pegawai')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request;
        }
        return null;
    }

    public function getIdPegawai($username){
        $request = $this->db->select('id_pegawai')->from('pegawai')->where(array('username' => $username))->get()->row();
        if($request != null){
            return $request->id_pegawai;
        }
        return null;
    }

    public function getDokter($request){
        return $this->db->select('p.id_pegawai as id_pegawai, p.nama_pegawai as nama_pegawai')->from('pegawai p')->join('detil_jadwal dj','dj.id_pegawai = p.id_pegawai')->where(array('dj.id_jadwal' => $request,'p.id_role_pegawai'=>'3'))->get()->result();
    }

    public function getBeautician($request){
        return $this->db->select('p.id_pegawai as id_pegawai, p.nama_pegawai as nama_pegawai')->from('pegawai p')->join('detil_jadwal dj','dj.id_pegawai = p.id_pegawai')->where(array('p.jk_pegawai' => $request->jk_pegawai,'p.id_role_pegawai'=>'4','isAvailable'=>'1'))->get()->result();
    }


    public function store($request) {
        $this->id_role_pegawai = $request->id_role_pegawai;
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->no_telp_pegawai = $request->no_telp_pegawai;
        $this->jk_pegawai = $request->jk_pegawai;
        $this->username = $request->username;
        $this->password = $request->password;
        $this->isAvailable = 1;
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

    public function updatePass($request) {
        $this->id_pegawai = $request->id_pegawai;
        $this->password = $request->password;
        $data = array( 
            'password'      => $this->password, 
        );
        if($this->db->where(array('id_pegawai' => $this->id_pegawai))->update($this->table, $data)){
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
        $this->id_pegawai = $request->id_pegawai;
        $this->id_role_pegawai = $request->id_role_pegawai;
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->no_telp_pegawai = $request->no_telp_pegawai;
        $this->jk_pegawai = $request->jk_pegawai;
        $this->username = $request->username;
        $data = array( 
            'id_role_pegawai'      => $this->id_role_pegawai, 
            'nama_pegawai'      => $this->nama_pegawai, 
            'alamat_pegawai'      => $this->alamat_pegawai,
            'no_telp_pegawai'      => $this->no_telp_pegawai, 
            'jk_pegawai'      => $this->jk_pegawai,
            'username'      => $this->username,
        );
        if($this->db->where(array('id_pegawai' => $this->id_pegawai))->update($this->table, $data)){
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
        $this->id_pegawai = $request->id_pegawai;
        $data = array( 
            'isDelete'      => 1,
        );
        if($this->db->where(array('id_pegawai' => $this->id_pegawai))->update($this->table, $data)){
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

    public function setAvailable($request){
        $this->id_pegawai = $request;
        $data = array( 
            'isAvailable'      => 1,
        );
        if($this->db->where(array('id_pegawai' => $this->id_pegawai))->update($this->table, $data)){
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

    public function setUnavailable($request){
        $this->id_pegawai = $request;
        $data = array( 
            'isAvailable'      => 0,
        );
        if($this->db->where(array('id_pegawai' => $this->id_pegawai))->update($this->table, $data)){
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