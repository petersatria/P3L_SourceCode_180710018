<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class userModel extends CI_Model
{
    private $table = 'user';
    public $id;
    public $nama;
    public $npm;
    public $fakultas;
    public $prodi;
    public $email;
    public $telp;
    public $username;
    public $password;
    public $status;
    public $rule = [
        [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required'
        ],
        [
            'field' => 'npm',
            'label' => 'npm',
            'rules' => 'required'
        ],
        [
            'field' => 'fakultas',
            'label' => 'fakultas',
            'rules' => 'required'
        ],
        [
            'field' => 'prodi',
            'label' => 'prodi',
            'rules' => 'required'
        ],
        [
            'field' => 'telp',
            'label' => 'telp',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function getAll() { 
        return $this->db->get('user')->result();
    }
    public function getOne($id){
        $result = $this->db->where('id',$id)->get('user')->row_array();
        if($result!=null)
            return [
                'msg'=>$result,
                'error'=>false
            ];
        else
            return [
                'msg'=>'User tidak ditemukan',
                'error'=>true
            ];
    }
    public function store($request) {
        $this->nama = $request->nama;
        $this->email = $request->email;
        $this->npm = $request->npm;
        $this->fakultas = $request->fakultas;
        $this->prodi = $request->prodi;
        $this->telp = $request->telp;
        $this->username = $request->username;
        $this->status = 'Waiting Confirm';
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
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
    public function update($request,$id) {
        $updateData = [ 
            'nama' =>$request->nama,
            'npm' =>$request->npm,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
            'telp' => $request->telp
        ];
        if($this->db->where('id',$id)->update($this->table, $updateData)){
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
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) 
            return [
                'msg'=>'Id tidak ditemukan',
                'error'=>true
            ];

        if($this->db->delete($this->table, array('id' => $id))){
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
    public function confirm($username){
        if (empty($this->db->select('*')->where(array('username' => $username))->get($this->table)->row())) 
            return [
                'msg'=>'Id tidak ditemukan',
                'error'=>true
            ];
        $updateData = [ 
            'status' =>'Confirm'
        ];
        if($this->db->where('username',$username)->update($this->table, $updateData)){
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
    public function login($request){
        $get=$this->db->where('username',$request->username)->get($this->table)->row_array();
        if($get!=null){
            if(password_verify($request->password,$get['password'])){
                $result=[
                    'id'=>$get['id'],
                    'status'=>$get['status']
                ];
                return [
                    'msg'=>$result,
                    'error'=>false
                ];
            }
            return[
                'msg'=>'wrong password',
                'error'=>true
            ];
        }else{
            return[
                'msg'=>'data tidak ditemukan',
                'error'=>true
            ]; 
        }
        
    }
}
