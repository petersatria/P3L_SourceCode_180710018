<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PerawatanModel extends CI_Model
{
    private $table = 'perawatan';
    public $id_prw;
    public $nama_prw;
    public $deskripsi_prw;
    public $harga_prw;
    public $poin_prw;
    public $isMedis;
    public $isDelete;
    public $rule = [
        [
            'field' => 'nama_prw',
            'label' => 'nama_prw',
            'rules' => 'required'
        ],
        [
            'field' => 'deskripsi_prw',
            'label' => 'deskripsi_prw',
            'rules' => 'required'
        ],
        [
            'field' => 'harga_prw',
            'label' => 'harga_prw',
            'rules' => 'required'
        ],
        [
            'field' => 'poin_prw',
            'label' => 'poin_prw',
            'rules' => 'required'
        ],
        [
            'field' => 'isMedis',
            'label' => 'isMedis',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id_prw,nama_prw,deskripsi_prw,harga_prw,poin_prw,isMedis')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id_prw,nama_prw,harga_prw,deskripsi_prw,poin_prw,isMedis')->from($this->table)->where(array('id_prw'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id_prw,nama_prw,deskripsi_prw,harga_prw,deskripsi_prw,poin_prw,isMedis')->from($this->table)->where(array('id_prw'=>$request,))->get()->row();
    }

    public function searchByString($request){
        return $this->db->select('id_prw,nama_prw,deskripsi_prw,harga_prw,deskripsi_prw,poin_prw,isMedis')->from($this->table)->where(array('isDelete'=>0))->like('nama_prw',$request)->or_like('nama_prw',$request,'before')->or_like('nama_prw',$request,'after')->get()->result();
    }


    public function store($request) {
        $this->nama_prw = $request->nama_prw;
        $this->deskripsi_prw = $request->deskripsi_prw;
        $this->harga_prw = $request->harga_prw;
        $this->poin_prw = $request->poin_prw;
        $this->isMedis = $request->isMedis;
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
        $this->id_prw = $request->id_prw;
        $this->nama_prw = $request->nama_prw;
        $this->harga_prw = $request->harga_prw;
        $this->deskripsi_prw = $request->deskripsi_prw;
        $this->poin_prw = $request->poin_prw;
        $this->isMedis = $request->isMedis;
        $data = array( 
            'nama_prw'      => $this->nama_prw, 
            'deskripsi_prw'      => $this->deskripsi_prw, 
            'harga_prw'      => $this->harga_prw,
            'deskripsi_prw'      => $this->deskripsi_prw, 
            'poin_prw'      => $this->poin_prw,
            'isMedis'      => $this->isMedis,
        );
        if($this->db->where(array('id_prw' => $this->id_prw))->update($this->table, $data)){
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
        $this->id_prw = $request->id_prw;
        $data = array( 
            'isDelete'      => 1,
        );
        if($this->db->where(array('id_prw' => $this->id_prw))->update($this->table, $data)){
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