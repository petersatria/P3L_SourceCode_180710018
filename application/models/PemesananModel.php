<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PemesananModel extends CI_Model
{
    private $table = 'pemesanan';
    public $id;
    public $no_PO;
    public $tgl_pemesanan;
    public $id_supplier;
    public $status;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'no_PO',
            'label' => 'no_PO',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_pemesanan',
            'label' => 'tgl_pemesanan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_supplier',
            'label' => 'id_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'status',
            'label' => 'status',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}