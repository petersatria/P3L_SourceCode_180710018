<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PenanggungJawabModel extends CI_Model
{
    private $table = 'penanggung_jawab';
    public $kode_transaksi;
    public $id_pegawai;
    public $rule = [
        [
            'field' => 'kode_transaksi',
            'label' => 'kode_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'id_pegawai',
            'label' => 'id_pegawai',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function getByRole($kode,$role) { 
        $request = $this->db->select('p.nama_pegawai as nama_pegawai')->from('penanggung_jawab pj')->join('pegawai p','pj.id_pegawai = p.id_pegawai')->where(array('pj.kode_transaksi'=>$kode,'p.id_role_pegawai'=>$role))->get()->row();
        if($request != null){
            return $request->nama_pegawai;
        }
        return '-';
    }

    public function getByTransaksi($kode){
        return $this->db->select('p.nama_pegawai as nama_pegawai, p.id_role_pegawai')->from('penanggung_jawab pj')->join('pegawai p','pj.id_pegawai = p.id_pegawai')->where(array('pj.kode_transaksi'=>$kode))->get()->result();
    }

    public function store($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->id_pegawai = $request->id_pegawai;
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