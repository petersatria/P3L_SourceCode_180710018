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
            'field' => 'id_supplier',
            'label' => 'id_supplier',
            'rules' => 'required'
        ]
    ];

    public function Rules() { 
        return $this->rule; 
    }

    public function get(){
        return $this->db->select('id, no_PO, id_supplier, tgl_pemesanan, status')->from($this->table)->get()->result();
    }

    public function search($id){
        return $this->db->select('id, no_PO, id_supplier, tgl_pemesanan, status')->from($this->table)->where(array('id'=>$id))->get()->row();
    }

    public function searchByString($request){
        return $this->db->select('id, no_PO, id_supplier, tgl_pemesanan, status')->from($this->table)->group_start()->like('no_PO',$request)->or_like('no_PO',$request,'before')->or_like('no_PO',$request,'after')->group_end()->get()->result();
    }

    public function store($request) {
        $this->id_supplier = $request->id_supplier;
        $this->tgl_pemesanan = date('Y-m-d');
        $this->created_by = $request->created_by;
        $this->status = 'belum tercetak';
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            $this->id = $this->getId($this->created_at,$this->created_by);
            if($this->id != null){
                if((int)$this->id < 10){
                    $this->no_PO = 'PO-'.$this->tgl_pemesanan.'-0'.$this->id;
                }else{
                    $this->no_PO = 'PO-'.$this->tgl_pemesanan.'-'.$this->id;
                }
                return $this->storeNoPO();
            }
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function storeNoPO(){
        $data = array( 
            'no_PO'      => $this->no_PO
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

    public function update($request){
        $this->id = $request->id;
        $this->id_supplier = $request->id_supplier;
        $this->updated_by = $this->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'id_supplier'      => $this->id_supplier, 
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

    public function printed($request){
        $this->id = $request->id;
        $this->updated_by = $this->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => 'tercetak', 
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

    public function received($request){
        $this->id = $request->id;
        $this->updated_by = $this->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => 'diterima', 
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

    public function getId($created_at, $created_by){
        $request = $this->db->select('id')->from($this->table)->where(array('created_by' => $created_by, 'created_at' => $created_at))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }
    
}