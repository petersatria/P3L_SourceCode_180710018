<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class ProdukModel extends CI_Model
{
    private $table = 'produk';
    public $id_prd;
    public $nama_prd;
    public $deskripsi_prd;
    public $harga_prd;
    public $stok_prd;
    public $ukuran_prd;
    public $satuan_prd;
    public $isDelete;
    public $rule = [
        [
            'field' => 'nama_prd',
            'label' => 'nama_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'deskripsi_prd',
            'label' => 'deskripsi_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'harga_prd',
            'label' => 'harga_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'stok_prd',
            'label' => 'stok_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'ukuran_prd',
            'label' => 'ukuran_prd',
            'rules' => 'required'
        ],
        [
            'field' => 'satuan_prd',
            'label' => 'satuan_prd',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id_prd,nama_prd,deskripsi_prd,harga_prd,satuan_prd,stok_prd,ukuran_prd')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id_prd,nama_prd,harga_prd,satuan_prd,deskripsi_prd,stok_prd,ukuran_prd')->from($this->table)->where(array('id_prd'=>$request,'isDelete'=>0))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('id_prd,nama_prd,deskripsi_prd,harga_prd,satuan_prd,deskripsi_prd,stok_prd,ukuran_prd')->from($this->table)->where(array('id_prd'=>$request,))->get()->row();
    }

    public function searchByString($request){
        return $this->db->select('id_prd,nama_prd,deskripsi_prd,harga_prd,satuan_prd,deskripsi_prd,stok_prd,ukuran_prd')->from($this->table)->where(array('isDelete'=>0))->like('nama_prd',$request)->or_like('nama_prd',$request,'before')->or_like('nama_prd',$request,'after')->get()->result();
    }


    public function getJumlahProduk($id_prd){
        return $this->db->select('stok_prd')->from($this->table)->where(array('id_prd'=>$id_prd))->get()->row()->stok_prd;
    }


    public function store($request) {
        $this->nama_prd = $request->nama_prd;
        $this->deskripsi_prd = $request->deskripsi_prd;
        $this->harga_prd = $request->harga_prd;
        $this->satuan_prd = $request->satuan_prd;
        $this->stok_prd = $request->stok_prd;
        $this->ukuran_prd = $request->ukuran_prd;
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
        $this->id_prd = $request->id_prd;
        $this->nama_prd = $request->nama_prd;
        $this->harga_prd = $request->harga_prd;
        $this->satuan_prd = $request->satuan_prd;
        $this->deskripsi_prd = $request->deskripsi_prd;
        $this->stok_prd = $request->stok_prd;
        $this->ukuran_prd = $request->ukuran_prd;
        $data = array( 
            'nama_prd'      => $this->nama_prd, 
            'deskripsi_prd'      => $this->deskripsi_prd, 
            'harga_prd'      => $this->harga_prd, 
            'satuan_prd'      => $this->satuan_prd, 
            'deskripsi_prd'      => $this->deskripsi_prd, 
            'stok_prd'      => $this->stok_prd,
            'ukuran_prd'      => $this->ukuran_prd,
        );
        if($this->db->where(array('id_prd' => $this->id_prd))->update($this->table, $data)){
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

    public function updateStock($request) {
        $this->id_prd = $request->id_prd;
        $this->stok_prd = $request->stok_prd;
        $data = array(  
            'stok_prd'      => $this->stok_prd,
        );
        if($this->db->where(array('id_prd' => $this->id_prd))->update($this->table, $data)){
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
        $this->id_prd = $request->id_prd;
        $data = array( 
            'isDelete'      => 1,
        );
        if($this->db->where(array('id_prd' => $this->id_prd))->update($this->table, $data)){
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