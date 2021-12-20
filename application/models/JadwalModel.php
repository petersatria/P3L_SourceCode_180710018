<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class JadwalModel extends CI_Model
{
    private $table = 'jadwal';
    public $id_jadwal;
    public $hari;
    public $shift;
    public $jam_mulai;
    public $jam_selesai;

    public function get() { 
        return $this->db->select('id_jadwal,hari,shift,jam_mulai,jam_selesai')->from($this->table)->get()->result();
    }

    public function search($request){
        return $this->db->select('id_jadwal,hari,shift,jam_mulai,jam_selesai')->from($this->table)->where(array('id_jadwal'=>$request))->get()->row();
    }

    public function getCurrent(){
        $day = date('D');
        switch ($day) {
            case 'Tue':
                $day = 'Selasa';
                break;
            case 'Wed':
                $day = 'Rabu';
                break;
            case 'Thu':
                $day = 'Kamis';
                break;
            case 'Fri':
                $day = "Jum'at";
                break;
            case 'Sat':
                $day = 'Sabtu';
                break;
            case 'Sun':
                $day = 'Minggu';
                break;
            default:
                break;
        }
        $currHour = date('H:i:s');
        return $this->db->select('id_jadwal')->from($this->table)->where(array('hari'=>$day,'jam_selesai >'=>$currHour,'jam_mulai <'=>$currHour))->get()->row()->id_jadwal;
    }
}