<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PembayaranLayananModel extends CI_Model
{
    private $table = 'pembayaran_layanan';
    public $id;
    public $id_transaksi;
    public $diskon;
    public $bayar;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_transaksi',
            'label' => 'id_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'diskon',
            'label' => 'diskon',
            'rules' => 'required'
        ],
        [
            'field' => 'bayar',
            'label' => 'bayar',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($id_transaksi){
        return $this->db->select('id_transaksi,diskon,bayar')->from($this->table)->where(array('id_transaksi'=>$id_transaksi))->get()->row();
    }

    public function isPayed($id_transaksi){
        $result = $this->db->select('id')->from($this->table)->where(array('id_transaksi'=>$id_transaksi))->get()->row();
        if($result != null){
            return $result->id;
        }
        return null;
    }

    public function store($request) {
        $this->id_transaksi = $request->id_transaksi;
        $this->diskon = $request->diskon;
        $this->bayar = $request->bayar;
        $this->created_by = $request->created_by;
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            return $this->updateTransaksi($this->created_at,$this->created_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateTransaksi($updated_at,$updated_by){
        $data = array( 
            'updated_by'      => $updated_by, 
            'updated_at'      => $updated_at
        );
        if($this->db->where(array('id' => $this->id_transaksi))->update('transaksi_layanan', $data)){
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