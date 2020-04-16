<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiLayananModel extends CI_Model
{
    private $table = 'detil_transaksi_layanan';
    public $id;
    public $id_layanan;
    public $id_hewan;
    public $harga;
    public $jumlah;
    public $id_transaksi;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_hewan',
            'label' => 'id_hewan',
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
        ],
        [
            'field' => 'id_transaksi',
            'label' => 'id_transaksi',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}