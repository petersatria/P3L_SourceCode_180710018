<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class TransaksiPenjualanModel extends CI_Model
{
    private $table = 'transaksi_penjualan';
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
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->group_by('tp.id')->get()->result();
    }

    public function get() { 
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp,IFNULL(sum(dt.harga),0) as total, 0 as isTransaksiLayanan, status, 0 as isPayed')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->like('status','belum lunas')->group_by('tp.id')->get()->result();
    }

    public function searchForAdmin($request){
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->group_by('tp.id')->where(array('tp.id'=>$request))->get()->row();
    }

    public function search($request){
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp,IFNULL(sum(dt.harga),0) as total, 0 as isTransaksiLayanan, status, 0 as isPayed')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->like('status','belum lunas')->group_by('tp.id')->where(array('tp.id'=>$request))->get()->row();
    }
    
    public function searchByStringAdmin($request){
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp, id_CS, id_cashier, status, IFNULL(sum(dt.harga),0) as total')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->group_by('tp.id')->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->get()->result();
    }

    public function searchByString($request){
        return $this->db->select('tp.id,tp.no_transaksi,tp.no_telp,IFNULL(sum(dt.harga),0) as total, 0 as isTransaksiLayanan, status, 0 as isPayed')->from('transaksi_penjualan tp')->join('detil_transaksi_penjualan dt','tp.id = dt.id_transaksi','left')->like('status','belum lunas')->group_start()->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->group_end()->group_by('tp.id')->get()->result();
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
                    $this->no_transaksi = 'PR-'.$this->no_transaksi.'-0'.$this->id;
                }else{
                    $this->no_transaksi = 'PR-'.$this->no_transaksi.'-'.$this->id;
                }
                return $this->storeNoTransaksi();
            }
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function update($request){
        $this->id = $request->id;
        $this->is_member = $request->is_member;
        $this->no_telp = $request->no_telp;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'no_telp'      => $this->no_telp,
            'is_member'      => $this->is_member,
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

    public function pay($request) {
        $this->id = $request->id;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status' => 'lunas',
            'updated_by' => $this->updated_by, 
            'updated_at' => $this->updated_at
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