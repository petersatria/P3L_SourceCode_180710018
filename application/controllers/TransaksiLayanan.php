<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class TransaksiLayanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('TransaksiLayananModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function admin_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiLayananModel->getForAdmin();
			foreach($response as $r){
                if($r->id_cashier != null){
                    $r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
                }
                $r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
            }
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiLayananModel->searchForAdmin($id);
			foreach($response as $r){
                if($r->id_cashier != null){
                    $r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
                }
                $r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
            }
			return $this->returnData($response,false);
		}
	}

	public function byString_get($nama = null){
		$response = $this->TransaksiLayananModel->searchByString($nama);
		foreach($response as $r){
            if($r->id_cashier != null){
                $r->id_cashier = $this->pegawai->searchForeign($r->id_cashier)->nama;
            }
            $r->id_CS = $this->pegawai->searchForeign($r->id_CS)->nama;
        }
		return $this->returnData($response, false);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->TransaksiLayananModel->rules();
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
			$ukuran = new data();
			$ukuran->id = $id;
            $ukuran->nama = $this->post('nama');
            $ukuran->id_jenis_hewan = $this->post('id_jenis_hewan');
            $ukuran->tanggal_lahir = $this->post('tanggal_lahir');
			$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
			$response = $this->TransaksiLayananModel->update($ukuran);
		}else{
			$ukuran = new data();
			$ukuran->nama = $this->post('nama');
            $ukuran->id_jenis_hewan = $this->post('id_jenis_hewan');
            $ukuran->tanggal_lahir = $this->post('tanggal_lahir');
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			$response = $this->TransaksiLayananModel->store($ukuran);
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
		$response = $this->TransaksiLayananModel->delete($ukuran);
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
    public $id_jenis_hewan;
    public $tanggal_lahir;
	public $created_by;
	public $updaetd_by;
}
