<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilPemesananModel extends CI_Model
{
    private $table = 'detil_pemesanan';
    public $id;
    public $id_pemesanan;
    public $id_produk;
    public $jumlah;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'id_pemesanan',
            'label' => 'id_pemesanan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}