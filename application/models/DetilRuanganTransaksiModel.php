<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilRuanganTransaksiModel extends CI_Model
{
    private $table = 'detil_ruangan_transaksi';
    public $kode_transaksi;
    public $no_ruangan;
    public $rule = [
        [
            'field' => 'kode_transaksi',
            'label' => 'kode_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'no_ruangan',
            'label' => 'no_ruangan',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function getByTransaksi($kode){
        return $this->db->select('no_ruangan')->from($this->table)->where(array('kode_transaksi'=>$kode))->get()->row();
    }

    public function store($no_ruangan,$kode_transaksi) {
        $this->kode_transaksi = $kode_transaksi;
        $this->no_ruangan = $no_ruangan;
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
}