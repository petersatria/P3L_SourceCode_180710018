<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class TransaksiLayananModel extends CI_Model
{
    private $table = 'transaksi_layanan';
    public $id;
    public $no_transaksi;
    public $isMember;
    public $no_telp;
    public $id_cashier;
    public $id_CS;
    public $tgl_transaksi;
    public $status;
    public $isDelete;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'no_transaksi',
            'label' => 'no_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'isMember',
            'label' => 'isMember',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp',
            'label' => 'no_telp',
            'rules' => 'required'
        ],
        [
            'field' => 'id_cashier',
            'label' => 'id_cashier',
            'rules' => 'required'
        ],
        [
            'field' => 'id_CS',
            'label' => 'id_CS',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_transaksi',
            'label' => 'tgl_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'status',
            'label' => 'status',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}