<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Pemesanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('PemesananModel');
		$this->load->model('PegawaiModel');
		$this->load->model('SupplierModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
			$response = $this->PemesananModel->get();
			foreach($response as $r){
                $r->id_supplier = $this->SupplierModel->searchForeign($r->id_supplier)->nama;
            }
			return $this->returnData($response, false);
        }else{
			$response = $this->PemesananModel->search($id);
			$response->id_supplier = $this->SupplierModel->searchForeign($response->id_supplier)->nama;
			return $this->returnData($response,false);
		}
	}

	public function byString_get($nama = null){
		$response = $this->PemesananModel->searchByString($nama);
		foreach($response as $r){
            $r->id_supplier = $this->SupplierModel->searchForeign($r->id_supplier)->nama;
        }
		return $this->returnData($response, false);
	}


	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->PemesananModel->rules();
		if($id != null){
			array_push(
				$rule,
				[
					'field' => 'updated_by',
					'label' => 'updated_by',
					'rules' => 'required'
				]
			);
		}else{
			array_push(
				$rule,
				[
					'field' => 'created_by',
					'label' => 'craeted_by',
					'rules' => 'required'
				]
			);
		}
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		if($id != null){
			$data = new data();
			$data->id = $id;
			$data->id_supplier = $this->post('id_supplier');
			$data->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
			$response = $this->PemesananModel->update($data);
		}else{
			$data = new data();
			$data->id_supplier = $this->post('id_supplier');
			$data->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			$response = $this->PemesananModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function printed_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$validation = $this->form_validation;
		$rule = [
			[
				'field' => 'updated_by',
				'label' => 'updated_by',
				'rules' => 'required'
			]
		];
		$validation->set_rules($rule);
		if(!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		$ukuran = new data();
		$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
		$ukuran->id = $id;
		$response = $this->PemesananModel->printed($ukuran);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function received_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$validation = $this->form_validation;
		$rule = [
			[
				'field' => 'updated_by',
				'label' => 'updated_by',
				'rules' => 'required'
			]
		];
		$validation->set_rules($rule);
		if(!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		$ukuran = new data();
		$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
		$ukuran->id = $id;
		$response = $this->PemesananModel->received($ukuran);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function cancel_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$validation = $this->form_validation;
		$rule = [
			[
				'field' => 'updated_by',
				'label' => 'updated_by',
				'rules' => 'required'
			]
		];
		$validation->set_rules($rule);
		if(!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		$ukuran = new data();
		$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
		$ukuran->id = $id;
		$response = $this->PemesananModel->cancel($ukuran);
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
	public $id;
    public $no_PO;
    public $tgl_pemesanan;
    public $id_supplier;
    public $status;
    public $isDelete;
	public $created_by;
	public $updaetd_by;
}
