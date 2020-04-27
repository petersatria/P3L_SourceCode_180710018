<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilTransaksiLayananModel extends CI_Model
{
    private $table = 'detil_transaksi_layanan';
    public $id;
    public $id_layanan;
    public $id_hewan;
    public $harga;
    public $jumlah;
    public $id_transaksi;
    public $updated_by;
    public $updated_at;
    public $created_by;
    public $created_at;
    public $rule = [
        [
            'field' => 'nama_hewan',
            'label' => 'nama_hewan',
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
        ],
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ],
        [
            'field' => 'id_transaksi',
            'label' => 'id_transaksi',
            'rules' => 'required'
        ]
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get($id_transaksi){
        return $this->db->select('dt.id,dt.id_layanan,dt.id_hewan,dt.harga,dt.jumlah,(dt.harga * dt.jumlah) as subtotal,l.url_gambar')->from('detil_transaksi_layanan dt')->join('layanan l','l.id = dt.id_layanan')->where(array('dt.id_transaksi'=>$id_transaksi))->get()->result();
    }

    public function search($id){
        return $this->db->select('id,id_layanan,id_hewan,harga,jumlah')->from($this->table)->where(array('id'=>$id))->get()->result();
    }

    public function getIdHewanById($id){
        return $this->db->select('id_hewan')->from($this->table)->where(array('id'=>$id))->get()->row()->id_hewan;
    }

    public function getIdTransaksi(){
        return $this->db->select('id_transaksi')->from($this->table)->where(array('id'=>$this->id))->get()->row()->id_transaksi;
    }

    public function store($request) {
        $this->id_hewan = $request->id_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->id_transaksi = $request->id_transaksi;
        $this->harga = $request->harga;
        $this->jumlah = $request->jumlah;
        $this->created_by = $request->created_by;
        $this->created_at = date('Y-m-d H:i:s');
        if($this->db->insert($this->table, $this)){
            return $this->updateTransaksi($this->created_at,$this->created_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function update($request) {
        $this->id = $request->id;
        $this->id_hewan = $request->id_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->id_transaksi = $request->id_transaksi;
        $this->harga = $request->harga;
        $this->jumlah = $request->jumlah;
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        $data = array( 
            'id_hewan'  => $this->id_hewan,
            'id_layanan'    => $this->id_layanan,
            'harga'     => $this->harga,
            'jumlah'      => $this->jumlah, 
            'updated_by'      => $this->updated_by, 
            'updated_at'      => $this->updated_at
        );
        if($this->db->where(array('id' => $this->id))->update($this->table, $data)){
            return $this->updateTransaksi($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function delete($request){
        $this->id = $request->id;
        $this->id_transaksi = $this->getIdTransaksi();
        $this->updated_by = $request->updated_by;
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->db->where(array('id' => $this->id))->delete($this->table)){
            return $this->updateTransaksi($this->updated_at,$this->updated_by);
        }
        return [
            'msg'=>'Gagal',
            'error'=>true
        ];
    }

    public function updateTransaksi($updated_at,$updated_by){
        $data = array( 
            'updated_by'      => $updated_by, 
            'updated_at'      => $updated_at
        );
        if($this->db->where(array('id' => $this->id_transaksi))->update('transaksi_layanan', $data)){
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