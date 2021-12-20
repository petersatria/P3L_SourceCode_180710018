<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class CustomerModel extends CI_Model
{
    private $table = 'customer';
    public $kode_cust;
    public $nama_cust;
    public $alamat_cust;
    public $tgl_lahir_cust;
    public $jk_cust;
    public $no_telp_cust;
    public $email_cust;
    public $alergi_obat_cust;
    public $poin_cust;
    public $tgl_registrasi_cust;
    public $password_cust;
    public $rule = [
        [
            'field' => 'nama_cust',
            'label' => 'nama_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat_cust',
            'label' => 'alamat_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_lahir_cust',
            'label' => 'tgl_lahir_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'jk_cust',
            'label' => 'jk_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'no_telp_cust',
            'label' => 'no_telp_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'email_cust',
            'label' => 'email_cust',
            'rules' => 'required'
        ],
        [
            'field' => 'alergi_obat_cust',
            'label' => 'alergi_obat_cust',
            'rules' => 'required'
        ],
    ];
    public function Rules() { 
        return $this->rule; 
    }

    public function get() { 
        return $this->db->select('*')->from($this->table)->get()->result();
    }

    public function getRegisteredByYear($tahun){
        return $this->db->query('SELECT * FROM (SELECT bulans.bulan as bulan, "L" as jenis_kelamin, IFNULL(totals.total,0) as total FROM (
            SELECT "January" as bulan 
            UNION SELECT "February" as bulan 
            UNION SELECT "March" as bulan
            UNION SELECT "April" as bulan 
            UNION SELECT "May" as bulan 
            UNION SELECT "June" as bulan 
            UNION SELECT "July" as bulan 
            UNION SELECT "August" as bulan
            UNION SELECT "September" as bulan 
            UNION SELECT "October" as bulan 
            UNION SELECT "November" as bulan 
            UNION SELECT "December" as bulan) as bulans LEFT JOIN (SELECT date_format(tgl_registrasi_cust,"%M") as bulan, jk_cust as jenis_kelamin, count(kode_cust) as total FROM customer WHERE date_format(tgl_registrasi_cust,"%Y") = "'.$tahun.'" and jk_cust = "L" GROUP BY date_format(tgl_registrasi_cust,"%m"),jk_cust) as totals ON totals.bulan = bulans.bulan
                       UNION
                       SELECT bulans.bulan as bulan, "P" as jenis_kelamin, IFNULL(totals.total,0) as total FROM (
            SELECT "January" as bulan 
            UNION SELECT "February" as bulan 
            UNION SELECT "March" as bulan 
            UNION SELECT "April" as bulan 
            UNION SELECT "May" as bulan 
            UNION SELECT "June" as bulan 
            UNION SELECT "July" as bulan 
            UNION SELECT "August" as bulan
            UNION SELECT "September" as bulan 
            UNION SELECT "October" as bulan 
            UNION SELECT "November" as bulan 
            UNION SELECT "December" as bulan) as bulans LEFT JOIN (SELECT date_format(tgl_registrasi_cust,"%M") as bulan, jk_cust as jenis_kelamin, count(kode_cust) as total FROM customer WHERE date_format(tgl_registrasi_cust,"%Y") = "'.$tahun.'" and jk_cust = "P" GROUP BY date_format(tgl_registrasi_cust,"%m"),jk_cust) as totals ON totals.bulan = bulans.bulan)as temp ORDER BY temp.bulan')->result();
    }

    public function search($request){
        return $this->db->select('*')->from($this->table)->where(array('kode_cust'=>$request))->get()->row();
    }

    public function searchForeign($request){
        return $this->db->select('*')->from($this->table)->where(array('kode_cust'=>$request,))->get()->row();
    }

    public function login($username){
        $request = $this->db->select('kode_cust,password_cust')->from($this->table)->where(array('kode_cust' => $username))->get()->row();
        if($request != null){
            return $request;
        }
        return null;
    }

    public function getCount(){
        $request = $this->db->select('count(*) as total')->from($this->table)->get()->row();
        if($request != null){
            return $request->total;
        }
        return null;
    }


    public function store($request) {
        $this->kode_cust = $request->kode_cust;
        $this->nama_cust = $request->nama_cust;
        $this->alamat_cust = $request->alamat_cust;
        $this->tgl_lahir_cust = $request->tgl_lahir_cust;
        $this->jk_cust = $request->jk_cust;
        $this->no_telp_cust = $request->no_telp_cust;
        $this->email_cust = $request->email_cust;
        $this->alergi_obat_cust = $request->alergi_obat_cust;
        $this->password_cust = $request->password_cust;
        $this->tgl_registrasi_cust = $request->tgl_registrasi_cust;
        $this->poin_cust = 0;
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
        $this->kode_cust = $request->kode_cust;
        $this->nama_cust = $request->nama_cust;
        $this->alamat_cust = $request->alamat_cust;
        $this->tgl_lahir_cust = $request->tgl_lahir_cust;
        $this->jk_cust = $request->jk_cust;
        $this->no_telp_cust = $request->no_telp_cust;
        $this->email_cust = $request->email_cust;
        $this->alergi_obat_cust = $request->alergi_obat_cust;
        $data = array( 
            'nama_cust'      => $this->nama_cust, 
            'alamat_cust'      => $this->alamat_cust, 
            'tgl_lahir_cust'      => $this->tgl_lahir_cust,
            'jk_cust'      => $this->jk_cust, 
            'no_telp_cust'      => $this->no_telp_cust,
            'email_cust'      => $this->email_cust,
            'alergi_obat_cust'      => $this->alergi_obat_cust,
        );
        if($this->db->where(array('kode_cust' => $this->kode_cust))->update($this->table, $data)){
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

    public function updatePass($request) {
        $this->kode_cust = $request->kode_cust;
        $this->password_cust = $request->password_cust;
        $data = array( 
            'password_cust'      => $this->password_cust, 
        );
        if($this->db->where(array('kode_cust' => $this->kode_cust))->update($this->table, $data)){
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

    public function updatePoin($request) {
        $this->kode_cust = $request->kode_cust;
        $this->poin_cust = $request->poin_cust;
        $data = array( 
            'poin_cust'      => $this->poin_cust,
        );
        if($this->db->where(array('kode_cust' => $this->kode_cust))->update($this->table, $data)){
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