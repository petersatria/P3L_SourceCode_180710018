<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class TransaksiLayananModel extends CI_Model
{
    private $table = 'transaksi_layanan';
    public $id;
    public $no_transaksi;
    public $is_member;
    public $no_telp;
    public $id_cashier;
    public $id_CS;
    public $tgl_transaksi;
    public $status;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'is_member',
            'label' => 'is_member',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp',
            'label' => 'no_telp',
            'rules' => 'required'
        ],
        [
            'field' => 'id_CS',
            'label' => 'id_CS',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    
    public function getForAdmin() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->group_by('tl.id')->get()->result();
    }

    public function getForCashier() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum lunas')->group_by('tl.id')->get()->result();
    }

    public function getForCS() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum Selesai')->group_by('tl.id')->get()->result();
    }

    public function searchForAdmin($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->group_by('tl.id')->where(array('tl.id'=>$request))->get()->row();
    }

    public function searchForCS($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum Selesai')->group_by('tl.id')->where(array('tl.id'=>$request))->get()->row();
    }

    public function searchForCashier($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum lunas')->group_by('tl.id')->where(array('tl.id'=>$request))->get()->row();
    }
    
    public function searchByStringAdmin($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->group_by('tl.id')->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->get()->result();
    }

    public function searchByStringCashier($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum lunas')->group_start()->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->group_end()->group_by('tl.id')->get()->result();
    }

    public function searchByStringCS($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,IFNULL(sum(dt.harga),0) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi','left')->like('status','belum Selesai')->group_start()->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->group_end()->group_by('tl.id')->get()->result();
    }

    public function store($request) {
        $this->no_telp = $request->no_telp;
        $this->tgl_transaksi = date('Y-m-d');
        $this->is_member = $request->is_member;
        $this->id_CS = $request->id_CS;
        $this->created_by = $request->created_by;
        $this->status = $request->status;
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            $this->id = $this->getIdTransaksi($this->created_at,$this->created_by);
            if($this->id != null){
                $this->no_transaksi = date('dmy');
                if((int)$this->id < 10){
                    $this->no_transaksi = 'LY-'.$this->no_transaksi.'-0'.$this->id;
                }else{
                    $this->no_transaksi = 'LY-'.$this->no_transaksi.'-'.$this->id;
                }
                return $this->storeNoTransaksi();
            }
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function storeNoTransaksi(){
        $data = array( 
            'no_transaksi'      => $this->no_transaksi
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
            return [
                'msg'=> $this->id,
                'error'=>false
            ];
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }
    
    public function done($request) {
        $this->id = $request->id;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => 'belum lunas',
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
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

    public function pay($request) {
        $this->id = $request->id;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => 'lunas',
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
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

    public function cancel($request){
        $this->id = $request->id;
        $this->updated_by = $this->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => 'dibatalkan', 
            'updated_by' => $this->updated_by, 
            'updated_at'       => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
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

    public function getIdTransaksi($created_at, $created_by){
        $request = $this->db->select('id')->from($this->table)->where(array('created_by' => $created_by, 'created_at' => $created_at))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }

    
}