<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class LayananModel extends CI_Model
{
    private $table = 'layanan';
    public $id;
    public $harga;
    public $id_ukuran_hewan;
    public $id_layanan;
    public $url_gambar;
    public $isDelete;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'id_ukuran_hewan',
            'label' => 'id_ukuran_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('id,harga,id_ukuran_hewan,id_layanan,url_gambar')->from($this->table)->where(array('isDelete'=>0))->get()->result();
    }

    public function search($request){
        return $this->db->select('id,harga,id_ukuran_hewan,id_layanan,url_gambar')->from($this->table)->where(array('id'=>$request,'isDelete'=>0))->get()->result();
    }

    public function searchForeign($request){
        return $this->db->select('id,harga,id_ukuran_hewan,id_layanan,url_gambar')->from($this->table)->where(array('id'=>$request))->get()->result();
    }
    
    public function searchByString($request){
        return $this->db->select('layanan.id,nama as `id_layanan`,harga,id_ukuran_hewan,url_gambar')->from($this->table)->join('jenis_layanan','jenis_layanan.id = layanan.id_layanan')->where(array('layanan.isDelete'=>0))->like('nama',$request)->or_like('nama',$request,'before')->or_like('nama',$request,'after')->get()->result();
    }

    public function store($request) {
        $this->harga = $request->harga;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->url_gambar = $request->url_gambar;
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
        $this->harga = $request->harga;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->updated_by = $request->updated_by;
        $this->url_gambar = $request->url_gambar;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'harga'      => $this->harga, 
            'id_ukuran_hewan'      => $this->id_ukuran_hewan, 
            'id_layanan'      => $this->id_layanan,
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at, 
            'url_gambar'       => $this->url_gambar
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