<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Perawatan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('PerawatanModel');
		$this->load->model('DetilTransaksiPerawatanModel');
		$this->load->library('form_validation');
	}

	public function index_get($id_prw = null)
	{
        if($id_prw==null){
            $response = $this->PerawatanModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->PerawatanModel->search($id_prw);
			return $this->returnData($response,false);
		}
	}

	public function mobile_get($id_prd = null)
	{
        if($id_prd==null){
            $response = $this->PerawatanModel->get();
			return $this->response($response);
        }else{
            $response = $this->PerawatanModel->search($id_prd);
			return $this->response($response);
		}
	}

	public function mobileBest_get()
	{
		$bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $date=$bulan.'-'.$tahun;
		$response = $this->DetilTransaksiPerawatanModel->getTotalMobile($date);
		return $this->response($response);
	}

	public function byString_get($nama_prw = null){
		$response = $this->PerawatanModel->searchByString($nama_prw);
		return $this->returnData($response, false);
	}

	public function index_post($id_prw = null)
	{
		$validation = $this->form_validation;
		$rule = $this->PerawatanModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->id_prw = $id_prw;
        $data->nama_prw = $this->post('nama_prw');
        $data->deskripsi_prw = $this->post('deskripsi_prw');
        $data->harga_prw = $this->post('harga_prw');
		$data->poin_prw = $this->post('poin_prw');
		$data->isMedis = $this->post('isMedis');
		if($id_prw != null){
			$response = $this->PerawatanModel->update($data);
		}else{
			$response = $this->PerawatanModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function delete_post($id_prw=null){
		if($id_prw == null){
			return $this->returnData('Parameter id_prw Tidak Ditemukan', true);
		}
		$ukuran = new data();
		$ukuran->id_prw = $id_prw;
		$response = $this->PerawatanModel->delete($ukuran);
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
	public $id_prw;
    public $nama_prw;
    public $deskripsi_prw;
    public $harga_prw;
    public $poin_prw;
	public $isMedis;
	public $ukuran_prd;
}