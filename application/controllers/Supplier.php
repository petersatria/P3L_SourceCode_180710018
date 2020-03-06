<?php

use Restserver\Libraries\REST_Controller;

class Supplier extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('SupplierModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->SupplierModel->get();
			return $this->returnData($response, false);
        }else{
			$response = $this->SupplierModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->SupplierModel->rules();
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
        $ukuran = new data();
        $ukuran->id = $id;
        $ukuran->nama = $this->post('nama');
        $ukuran->no_telp = $this->post('no_telp');
        $ukuran->alamat = $this->post('alamat');
        $ukuran->kota = $this->post('kota');
		if($id != null){
            $ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
			$response = $this->SupplierModel->update($ukuran);
		}else{
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			$response = $this->SupplierModel->store($ukuran);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function delete_post($id=null){
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
		$response = $this->SupplierModel->delete($ukuran);
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
    public $nama;
    public $no_telp;
    public $alamat;
    public $kota;
	public $created_by;
	public $updaetd_by;
}