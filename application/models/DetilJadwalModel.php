<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class DetilJadwalModel extends CI_Model
{
    private $table = 'detil_jadwal';
    public $id_jadwal;
    public $id_pegawai;
    public $rule = [
        [
            'field' => 'id_jadwal',
            'label' => 'id_jadwal',
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

    public function get() { 
        return $this->db->select('dj.id_jadwal as id_jadwal,dj.id_pegawai as id_pegawai, j.hari as hari, j.shift as shift, j.jam_mulai as jam_mulai, j.jam_selesai as jam_selesai, p.nama_pegawai as nama_pegawai, r.nama_role as role_pegawai')->from('detil_jadwal dj')->join('jadwal j','dj.id_jadwal = j.id_jadwal')->join('pegawai p','dj.id_pegawai = p.id_pegawai')->join('role r','r.id_role = p.id_role_pegawai')->get()->result();
    }

    public function getMobile() { 
        return $this->db->select('dj.id_jadwal as id_jadwal,dj.id_pegawai as id_pegawai, j.hari as hari, j.shift as shift, j.jam_mulai as jam_mulai, j.jam_selesai as jam_selesai, p.nama_pegawai as nama_pegawai, r.nama_role as role_pegawai')->from('detil_jadwal dj')->join('jadwal j','dj.id_jadwal = j.id_jadwal')->join('pegawai p','dj.id_pegawai = p.id_pegawai')->join('role r','r.id_role = p.id_role_pegawai')->where(array('r.id_role'=>'3'))->order_by("id_jadwal", "asc")->get()->result();
    }


    public function search($request){
        return $this->db->select('dj.id_jadwal as id_jadwal,dj.id_pegawai as id_pegawai, j.hari as hari, j.shift as shift, j.jam_mulai as jam_mulai, j.jam_selesai as jam_selesai, p.nama_pegawai as nama_pegawai, r.nama_role as role_pegawai')->from('detil_jadwal dj')->join('jadwal j','dj.id_jadwal = j.id_jadwal')->join('pegawai p','dj.id_pegawai = p.id_pegawai')->join('role r','r.id_role = p.id_role_pegawai')->where(array('dj.id_jadwal'=>$request->id_jadwal, 'dj.id_pegawai'=>$request->id_pegawai))->get()->row();
    }

    public function count($request){
        return $this->db->select('count(*) as total')->from($this->table)->where(array('id_pegawai'=>$request))->get()->row()->total;
    }

    public function store($request) {
        $this->id_jadwal = $request->id_jadwal;
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
    
    public function update($request,$oldJadwal) {
        $this->id_jadwal = $request->id_jadwal;
        $this->id_pegawai = $request->id_pegawai;
        $data = array( 
            'id_jadwal'      => $this->id_jadwal, 
            'id_pegawai'      => $this->id_pegawai,
        );
        if($this->db->where(array('id_jadwal'=>$oldJadwal, 'id_pegawai'=>$request->id_pegawai))->update($this->table, $data)){
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
        if($this->db->where(array('id_jadwal'=>$request->id_jadwal, 'id_pegawai'=>$request->id_pegawai))->delete($this->table)){
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