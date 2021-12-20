<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Promo extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('PromoModel');
		$this->load->library('form_validation');
	}

	public function index_get($kode_promo = null)
	{
        if($kode_promo==null){
            $response = $this->PromoModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->PromoModel->search($kode_promo);
			return $this->returnData($response,false);
		}
	}

	public function mobile_get($kode_promo = null)
	{
        if($kode_promo==null){
            $response = $this->PromoModel->get();
			return $this->response($response);
        }else{
            $response = $this->PromoModel->search($kode_promo);
			return $this->returnData($response,false);
		}
	}

	public function cashier_get($tgl = null)
	{
        if($tgl == null){
			return $this->returnData('Parameter tgl Tidak Ditemukan', true);
		}else{
            $response = $this->PromoModel->getCashier($tgl);
			return $this->returnData($response,false);
		}
	}

	public function index_post($kode_promo = null)
	{
		$validation = $this->form_validation;
		$rule = $this->PromoModel->rules();
        if($kode_promo == null){
            array_push(
				$rule,
				[
					'field' => 'kode_promo',
					'label' => 'kode_promo',
					'rules' => 'is_unique[promo.kode_promo]'
				]
			);
        }
            
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->kode_promo = $this->post('kode_promo');
        $data->nama_promo = $this->post('nama_promo');
        $data->diskon = $this->post('diskon');
        $data->tgl_promo_start = date('Y-m-d',strtotime($this->post('tgl_promo_start')));
		$data->tgl_promo_end = date('Y-m-d',strtotime($this->post('tgl_promo_end')));
		$data->status = $this->post('status');
		if($data->tgl_promo_start > $data->tgl_promo_end){
			return $this->returnData('Tgl Mulai Lebih Besar dari Tgl Selesai',true);
		}
		if($kode_promo != null){
			$response = $this->PromoModel->update($data);
		}else{
			$response = $this->PromoModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function delete_post($kode_promo=null){
		if($kode_promo == null){
			return $this->returnData('Parameter kode_promo Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_promo = $kode_promo;
		$response = $this->PromoModel->delete($kode_promo);
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
	public $kode_promo;
    public $nama_promo;
    public $diskon;
    public $tgl_promo_start;
    public $tgl_promo_end;
	public $status;
}