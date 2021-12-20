<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Jadwal extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('JadwalModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->JadwalModel->get();
			return $this->returnData($response, false);
        }else{
			$response = $this->JadwalModel->search($id);
			return $this->returnData($response,false);
		}
	}
	
	public function current_get(){
		
		$response = $this->JadwalModel->getCurrent();
		return $this->returnData($response,false);
	}

	public function returnData($msg, $error)
	{
		$response['error'] = $error;
		$response['message'] = $msg;
		return $this->response($response);
	}
}
