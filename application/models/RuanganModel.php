<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class RuanganModel extends CI_Model
{
    private $table = 'ruangan';
    public $no_ruangan;
    public $keterangan;
    public $isAvailable;

    public function get() { 
        return $this->db->select('no_ruangan,keterangan,isAvailable')->from($this->table)->where(array('isAvailable'=>1))->get()->result();
    }

    public function search($request){
        return $this->db->select('no_ruangan,keterangan,isAvailable')->from($this->table)->where(array('no_ruangan'=>$request))->get()->row();
    }

    public function setAvailable($request){
        $this->no_ruangan = $request;
        $data = array( 
            'isAvailable'      => 1,
        );
        if($this->db->where(array('no_ruangan' => $this->no_ruangan))->update($this->table, $data)){
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

    public function setUnavailable($request){
        $this->no_ruangan = $request;
        $data = array( 
            'isAvailable'      => 0,
        );
        if($this->db->where(array('no_ruangan' => $this->no_ruangan))->update($this->table, $data)){
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