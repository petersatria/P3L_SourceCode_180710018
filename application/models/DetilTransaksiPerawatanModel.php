<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiPerawatanModel extends CI_Model
{
    private $table = 'detil_transaksi_perawatan';
    public $id_detil_transaksi_perawatan;
    public $kode_transaksi;
    public $id_perawatan;
    public $jumlah_prw;
    public $sub_total_prw;
    public $isPoinUsed;
    public $rule = [
        [
            'field' => 'kode_transaksi',
            'label' => 'kode_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'id_perawatan',
            'label' => 'id_perawatan',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah_prw',
            'label' => 'jumlah_prw',
            'rules' => 'required'
        ],
        [
            'field' => 'sub_total_prw',
            'label' => 'sub_total_prw',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($request) { 
        return $this->db->select('dtp.id_detil_transaksi_perawatan as id_detil_transaksi_perawatan,dtp.kode_transaksi as kode_transaksi, dtp.id_perawatan as id_perawatan, p.nama_prw as nama_prw, p.isMedis as isMedis, dtp.jumlah_prw as jumlah_prw, dtp.sub_total_prw as sub_total_prw,p.harga_prw as harga_prw, p.poin_prw as poin_prw, dtp.isPoinUsed')->from('detil_transaksi_perawatan dtp')->join('perawatan p','p.id_prw = dtp.id_perawatan')->where(array('dtp.kode_transaksi'=>$request))->get()->result();
    }

    public function getTotalLaporan($request){
        return $this->db->query('SELECT p.nama_prw as nama_prw, IFNULL(total.total,0) as total, p.harga_prw as harga_prw FROM perawatan p LEFT JOIN (SELECT dtp.id_perawatan as id_perawatan, sum(dtp.jumlah_prw) as total FROM detil_transaksi_perawatan dtp JOIN transaksi t on t.kode_transaksi = dtp.kode_transaksi WHERE date_format(t.tgl_transaksi,"%m-%Y") = "'.$request.'" AND t.status_transaksi = "done" GROUP BY dtp.id_perawatan) as total ON p.id_prw = total.id_perawatan ORDER BY total desc LIMIT 10')->result();
    }

    public function getTotalMobile($request){
        return $this->db->query('SELECT p.nama_prw as nama_prw, IFNULL(total.total,0) as total, p.harga_prw as harga_prw, p.deskripsi_prw as deskripsi_prw FROM perawatan p LEFT JOIN (SELECT dtp.id_perawatan as id_perawatan, sum(dtp.jumlah_prw) as total FROM detil_transaksi_perawatan dtp JOIN transaksi t on t.kode_transaksi = dtp.kode_transaksi WHERE date_format(t.tgl_transaksi,"%m-%Y") = "'.$request.'" AND t.status_transaksi = "done" GROUP BY dtp.id_perawatan) as total ON p.id_prw = total.id_perawatan ORDER BY total desc LIMIT 10')->result();
    }


    public function search($request) { 
        return $this->db->select('*')->from($this->table)->where(array('id_detil_transaksi_perawatan'=>$request))->get()->row();
    }

    public function getCountIsMedis($request) { 
        $response = $this->db->select('count(*) as total')->from('detil_transaksi_perawatan dtp')->join('perawatan p','p.id_perawatan = dtp.id_prw')->where(array('dtp.kode_transaksi'=>$request,'p.isMedis'=>1))->get()->row();
        if($response!=null){
            return $response->total;
        }
        return null;
    }

    public function getId($request){
        $request = $this->db->select('*')->from($this->table)->where(array('kode_transaksi'=>$request->kode_transaksi,'id_perawatan'=>$request->id_perawatan))->get()->row();
        if($request!=null){
            return $request->id_detil_transaksi_perawatan;
        }
        return null;
    }

    public function store($request) {
        $this->kode_transaksi = $request->kode_transaksi;
        $this->id_perawatan = $request->id_perawatan;
        $this->jumlah_prw = $request->jumlah_prw;
        $this->sub_total_prw = $request->sub_total_prw;
        $this->isPoinUsed = 0;
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
        $this->id_detil_transaksi_perawatan = $request->id_detil_transaksi_perawatan;
        $this->jumlah_prw = $request->jumlah_prw;
        $this->sub_total_prw = $request->sub_total_prw;
        $data = array( 
            'jumlah_prw'      => $this->jumlah_prw,
            'sub_total_prw'      => $this->sub_total_prw,
        );
        if($this->db->where(array('id_detil_transaksi_perawatan' => $this->id_detil_transaksi_perawatan))->update($this->table, $data)){
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
    
    public function setUsedPoin($request) {
        $this->id_detil_transaksi_perawatan = $request->id_detil_transaksi_perawatan;
        $data = array( 
            'isPoinUsed'      => 1,
            'sub_total_prw'      => 0,
        );
        if($this->db->where(array('id_detil_transaksi_perawatan' => $this->id_detil_transaksi_perawatan))->update($this->table, $data)){
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
        if($this->db->where(array('id_detil_transaksi_perawatan'=>$request))->delete($this->table)){
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