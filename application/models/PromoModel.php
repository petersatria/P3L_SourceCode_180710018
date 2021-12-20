<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class PromoModel extends CI_Model
{
    private $table = 'promo';
    public $kode_promo;
    public $nama_promo;
    public $diskon;
    public $tgl_promo_start;
    public $tgl_promo_end;
    public $status;
    public $rule = [
        [
            'field' => 'kode_promo',
            'label' => 'kode_promo',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_promo',
            'label' => 'nama_promo',
            'rules' => 'required'
        ],
        [
            'field' => 'diskon',
            'label' => 'diskon',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_promo_start',
            'label' => 'tgl_promo_start',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_promo_end',
            'label' => 'tgl_promo_end',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('kode_promo,nama_promo,diskon,tgl_promo_start,tgl_promo_end,status')->from($this->table)->get()->result();
    }

    public function search($request){
        return $this->db->select('kode_promo,nama_promo,diskon,tgl_promo_start,tgl_promo_end,status')->from($this->table)->where(array('kode_promo'=>$request,))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('kode_promo,nama_promo,diskon,tgl_promo_start,tgl_promo_end,status')->from($this->table)->where(array('kode_promo'=>$request,))->get()->row();
    }

    public function getCashier($request){
        return $this->db->query("SELECT temp.kode_promo as kode_promo FROM (SELECT kode_promo, diskon FROM promo WHERE CURRENT_DATE BETWEEN tgl_promo_start and tgl_promo_end and status = 1) as temp INNER JOIN (SELECT kode_promo, diskon FROM promo WHERE kode_promo like 'POIN' UNION SELECT kode_promo, diskon FROM promo WHERE date_format(CURRENT_DATE,'%d%m') = date_format('".$request."','%d%m') AND kode_promo like 'BDAY' UNION SELECT kode_promo, diskon FROM promo WHERE TIMESTAMPDIFF(YEAR, STR_TO_DATE('".$request."','%Y-%d-%m'), CURRENT_DATE)  < 22 and kode_promo like 'MHS') as avail on avail.kode_promo = temp.kode_promo")->result();
    }

    public function store($request) {
        $this->kode_promo = $request->kode_promo;
        $this->nama_promo = $request->nama_promo;
        $this->diskon = $request->diskon;
        $this->tgl_promo_start = $request->tgl_promo_start;
        $this->tgl_promo_end = $request->tgl_promo_end;
        $this->status = $request->status;
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
    
    public function update($request) {
        $this->kode_promo = $request->kode_promo;
        $this->nama_promo = $request->nama_promo;
        $this->diskon = $request->diskon;
        $this->tgl_promo_start = $request->tgl_promo_start;
        $this->tgl_promo_end = $request->tgl_promo_end;
        $this->status = $request->status;
        $data = array( 
            'kode_promo'      => $this->kode_promo, 
            'nama_promo'      => $this->nama_promo, 
            'diskon'      => $this->diskon, 
            'tgl_promo_start'      => $this->tgl_promo_start, 
            'tgl_promo_end'      => $this->tgl_promo_end, 
            'status'      => $this->status
        );
        if($this->db->where(array('kode_promo' => $this->kode_promo))->update($this->table, $data)){
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

    public function delete($request){
        $this->id_prd = $request->id_prd;
        $data = array( 
            'status'      => 0,
        );
        if($this->db->where(array('kode_promo' => $this->kode_promo))->update($this->table, $data)){
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