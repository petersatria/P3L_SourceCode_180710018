<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class ProdukModel extends CI_Model
{
    private $table = 'produk';
    public $id;
    public $nama;
    public $id_kategori_produk;
    public $harga;
    public $satuan;
    public $jmlh_min;
    public $jmlh;
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
            'field' => 'id_kategori_produk',
            'label' => 'id_kategori_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'satuan',
            'label' => 'satuan',
            'rules' => 'required'
        ],
        [
            'field' => 'jmlh_min',
            'label' => 'jmlh_min',
            'rules' => 'required'
        ],
        [
            'field' => 'jmlh',
            'label' => 'jmlh',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,nama,id_kategori_produk,harga,satuan,jmlh_min,jmlh')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,nama,id_kategori_produk,harga,satuan,jmlh_min,jmlh')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id,nama,id_kategori_produk,harga,satuan,jmlh_min,jmlh')->from($this->table)->where(array('id'=>$request,))->get()->row();
    }

    public function searchByString($request){
        return $this->db->select('id,nama')->from($this->table)->where(array('isDelete'=>0))->like('nama',$request)->or_like('nama',$request,'before')->or_like('nama',$request,'after')->get()->result();
    }
    
    public function store($request) {
        $this->nama = $request->nama;
        $this->id_kategori_produk = $request->id_kategori_produk;
        $this->harga = $request->harga;
        $this->satuan = $request->satuan;
        $this->jmlh_min = $request->jmlh_min;
        $this->jmlh = $request->jmlh;
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
        $this->id_kategori_produk = $request->id_kategori_produk;
        $this->harga = $request->harga;
        $this->satuan = $request->satuan;
        $this->jmlh_min = $request->jmlh_min;
        $this->jmlh = $request->jmlh;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'nama'      => $this->nama, 
            'id_kategori_produk'      => $this->id_kategori_produk, 
            'harga'      => $this->harga, 
            'satuan'      => $this->satuan, 
            'jmlh_min'      => $this->jmlh_min, 
            'jmlh'      => $this->jmlh, 
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