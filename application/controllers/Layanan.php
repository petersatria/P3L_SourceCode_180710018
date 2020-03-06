<?php

use Restserver\Libraries\REST_Controller;

class Layanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
        $this->load->model('LayananModel');
        $this->load->model('UkuranHewanModel');
        $this->load->model('JenisLayananModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->LayananModel->get();
            foreach($response as $r){
				$r->id_ukuran_hewan = $this->UkuranHewanModel->searchForeign($r->id_ukuran_hewan)->nama;
				$r->id_layanan = $this->JenisLayananModel->searchForeign($r->id_layanan)->nama;
            }
			return $this->returnData($response, false);
        }else{
            $response = $this->LayananModel->search($id);
            foreach($response as $r){
				$r->id_ukuran_hewan = $this->UkuranHewanModel->searchForeign($r->id_ukuran_hewan)->nama;
				$r->id_layanan = $this->JenisLayananModel->searchForeign($r->id_layanan)->nama;
            }
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->LayananModel->rules();
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
        $data = new data();
        $data->id = $id;
        $data->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $data->harga = $this->post('harga');
		$data->id_layanan = $this->post('id_layanan');
		if($id != null){
            $data->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
			$response = $this->LayananModel->update($data);
		}else{
			$data->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			$response = $this->LayananModel->store($data);
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
		$response = $this->LayananModel->delete($ukuran);
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
    public $harga;
    public $id_ukuran_hewan;
    public $id_layanan;
	public $created_by;
	public $updaetd_by;
}