<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Ruangan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('RuanganModel');
		$this->load->model('DetilRuanganTransaksiModel');
		$this->load->library('form_validation');
	}

	public function index_get($no_ruangan = null)
	{
        if($no_ruangan==null){
            $response = $this->RuanganModel->get();
			return $this->returnData($response, false);
        }else{
			$response = $this->RuanganModel->search($no_ruangan);
			return $this->returnData($response,false);
		}
	}

	public function setAvailable_post($no_ruangan=null){
		if($no_ruangan == null){
			return $this->returnData('Parameter no_ruangan Tidak Ditemukan', true);
		}
		$response = $this->RuanganModel->setAvailable($no_ruangan);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function setUnavailable_post($no_ruangan=null){
		if($no_ruangan == null){
			return $this->returnData('Parameter no_ruangan Tidak Ditemukan', true);
		}
		$response = $this->RuanganModel->setUnavailable($no_ruangan);
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
	public $no_ruangan;
    public $keterangan;
}
