<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilJadwal extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('PegawaiModel');
		$this->load->model('DetilJadwalModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->DetilJadwalModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->DetilJadwalModel->search($id);
			$response->id_pegawai = $this->PegawaiModel->search($response->id_pegawai)->nama_pegawai;
			return $this->returnData($response,false);
		}
	}

	public function mobile_get($id = null)
	{
        if($id==null){
            $response = $this->DetilJadwalModel->getMobile();
			return $this->response($response);
        }else{
            $response = $this->DetilJadwalModel->search($id);
			$response->id_pegawai = $this->PegawaiModel->search($response->id_pegawai)->nama_pegawai;
			return $this->returnData($response,false);
		}
	}

	public function index_post($id_jadwal = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilJadwalModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->id_pegawai = $this->post('id_pegawai');
        $data->id_jadwal = $this->post('id_jadwal');
            
		if($this->DetilJadwalModel->count($this->post('id_pegawai')) <=6){
			if($this->DetilJadwalModel->search($data) == null){
				if($id_jadwal != null){
					$response = $this->DetilJadwalModel->update($data,$id_jadwal);
				}else{
					$response = $this->DetilJadwalModel->store($data);
				}
			}else{
				$response = [
					'msg'=>'Jadwal Shift Sudah Terdaftar',
					'error'=>true
				];
			}
		}else{
			$response = [
				'msg'=>'Jadwal Shift Sudah Maksimal',
				'error'=>true
			];
		}
		return $this->returnData($response['msg'], $response['error']);
	}


	public function delete_post(){
		$data = new data();
		$data->id_pegawai = $this->post('id_pegawai');
        $data->id_jadwal = $this->post('id_jadwal');
		$response = $this->DetilJadwalModel->delete($data);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function returnData($msg, $error)
	{
		$response['error'] = $error;
		$response['message'] = $msg;
		return $this->response($response);
	}
}

class data
{
	public $id_jadwal;
    public $id_pegawai;
}