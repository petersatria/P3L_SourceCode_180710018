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

    
    public function getForAdmin() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status,sum(dt.harga) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->get()->result();
    }

    public function getForCashier() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,sum(dt.harga) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->like('status','belum lunas')->get()->result();
    }

    public function getForCS() { 
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,sum(dt.harga) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->like('status','belum Selesai')->get()->result();
    }

    public function searchForAdmin($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status,sum(dt.harga) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->where(array('id'=>$request))->get()->result();
    }

    public function search($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, status,sum(dt.harga) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->where(array('id'=>$request))->get()->result();
    }
    
    public function searchByStringAdmin($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp, id_CS, id_cashier, status,sum(dt.harga) as total')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->get()->result();
    }

    public function searchByStringCashier($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,sum(dt.harga) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->like('status','belum lunas')->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->get()->result();
    }

    public function searchByStringCS($request){
        return $this->db->select('tl.id,tl.no_transaksi,tl.no_telp,sum(dt.harga) as total, 1 as isTransaksiLayanan, status')->from('transaksi_layanan tl')->join('detil_transaksi_layanan dt','tl.id = dt.id_transaksi')->like('status','belum selesai')->like('no_transaksi',$request)->or_like('no_transaksi',$request,'before')->or_like('no_transaksi',$request,'after')->get()->result();
    }

    public function store($request) {
        $this->no_telp = $request->no_telp;
        $this->tgl_transaksi = $request->tgl_transaksi;
        $this->alamat = $request->alamat;
        $this->created_by = $request->created_by;
        $this->status = $request->status;
        $this->created_at = date('Y-m-d H:i:s');
        $this->isDelete = 0;
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

    public function storeNoTransaksi($request){
        $this->id = $request->id;
        $this->no_transaksi = $request->no_transaksi;
        $data = array( 
            'no_transaksi'      => $this->no_transaksi
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
        $this->status = $request->status;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'status'      => $this->status,
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

    public function delete($request){
        $this->id = $request->id;
        $this->updated_by = $this->getIdPegawai($request->updated_by);
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

    public function getIdTransaksi($created_at, $craeted_by){
        $request = $this->db->select('id')->from($this->table)->where(array('created_by' => $created_by, 'created_at' => $created_at))->get()->row();
        if($request != null){
            return $request->id;
        }
        return null;
    }

    
}