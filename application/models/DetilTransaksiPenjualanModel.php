<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiPenjualanModel extends CI_Model
{
    private $table = 'detil_transaksi_penjualan';
    public $id;
    public $id_produk;
    public $id_transaksi;
    public $harga;
    public $jumlah;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'id_transaksi',
            'label' => 'id_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}