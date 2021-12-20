<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class RoleModel extends CI_Model
{
    private $table = 'role';
    public $id_role;
    public $nama_role;

    public function get() { 
        return $this->db->select('id_role,nama_role')->from($this->table)->get()->result();
    }

    public function search($request){
        return $this->db->select('id_role,nama_role')->from($this->table)->where(array('id_role'=>$request))->get()->row();
    }
}