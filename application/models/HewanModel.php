<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class HewanModel extends CI_Model
{
    private $table = 'hewan';
    public $id;
    public $nama;
    public $id_jenis_hewan;
    public $tanggal_lahir;
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
            'field' => 'id_jenis_hewan',
            'label' => 'id_jenis_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'tanggal_lahir',
            'label' => 'tanggal_lahir',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
}