<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class TransaksiPenjualan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('TransaksiPenjualanModel');
		$this->load->model('PegawaiModel');
		$this->load->model('MemberModel');
		$this->load->library('form_validation');
	}

	public function admin_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiPenjualanModel->getForAdmin();
			foreach($response as $r){
                if($r->id_cashier != null){
                    $r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
                }
                $r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
            }
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiPenjualanModel->searchForAdmin($id);
			if($response->id_cashier != null){
				$response->id_cashier = $this->PegawaiModel->searchForeign($response->id_cashier)->nama;
			}
			$response->id_CS = $this->PegawaiModel->searchForeign($response->id_CS)->nama;
			return $this->returnData($response,false);
		}
	}

	public function index_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiPenjualanModel->get();
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiPenjualanModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function adminByString_get($nama = null){
		$response = $this->TransaksiPenjualanModel->searchByStringAdmin($nama);
		foreach($response as $r){
			if($r->id_cashier != null){
				$r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
			}
			if($r->id_CS != null){
				$r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
			}
		}
		return $this->returnData($response, false);
	}

	public function ByString_get($nama = null){
		$response = $this->TransaksiPenjualanModel->searchByString($nama);
		return $this->returnData($response, false);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->TransaksiPenjualanModel->rules();
		if($id = null){
			array_push(
				$rule,
				[
					'field' => 'created_by',
					'label' => 'craeted_by',
					'rules' => 'required'
				]
			);
		}else{
			array_push(
				$rule,
				[
					'field' => 'updated_by',
					'label' => 'updated_by',
					'rules' => 'required'
				]
			);
		}
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		$ukuran = new data();
		if($id == null){
			$ukuran->is_member = $this->post('is_member');
			$ukuran->no_telp = $this->post('no_telp');
			$ukuran->id_CS = $this->PegawaiModel->getIdPegawai($this->post('id_CS'));
			$ukuran->status = 'belum lunas';
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			if($ukuran->is_member == '0' && $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$ukuran->is_member = '1';
			}
			if($ukuran->is_member == '0' || $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$response = $this->TransaksiPenjualanModel->store($ukuran);
			}else{
				$response = [
					'msg' => 'Member dengan Nomor Telepon tidak tersedia',
					'error' => true 
				];
			}
		}else{
			$ukuran->is_member = $this->post('is_member');
			$ukuran->no_telp = $this->post('no_telp');
			$ukuran->id_CS = $this->PegawaiModel->getIdPegawai($this->post('id_CS'));
			$ukuran->status = 'belum lunas';
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			if($ukuran->is_member == '0' && $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$ukuran->is_member = '1';
			}
			if($ukuran->is_member == '0' || $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$response = $this->TransaksiPenjualanModel->store($ukuran);
			}else{
				$response = [
					'msg' => 'Member dengan Nomor Telepon tidak tersedia',
					'error' => true 
				];
			}
		}
		
		
		return $this->returnData($response['msg'], $response['error']);
	}

	public function pay_post($id=null){
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
		$response = $this->TransaksiPenjualanModel->pay($ukuran);
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
		$response = $this->TransaksiPenjualanModel->cancel($ukuran);
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
    public $no_transaksi;
    public $is_member;
    public $no_telp;
    public $id_cashier;
    public $id_CS;
    public $tgl_transaksi;
    public $status;
    public $isDelete;
	public $created_by;
	public $updaetd_by;
}
