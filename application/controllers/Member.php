<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Member extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('MemberModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
			$response = $this->MemberModel->get();
			return $this->returnData($response, false);
        }else{
			$response = $this->MemberModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function byString_get($nama = null){
		$response = $this->MemberModel->searchByString($nama);
		return $this->returnData($response, false);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->MemberModel->rules();
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
                ],
                [
					'field' => 'no_telp',
					'label' => 'no_telp',
					'rules' => 'is_unique[member.no_telp]'
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
            $ukuran->no_telp = $this->post('no_telp');
            $ukuran->tanggal = $this->post('tanggal');
            $ukuran->alamat = $this->post('alamat');
            $ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
            if($this->MemberModel->getIdMemberByTelp($ukuran->no_telp) == null || $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) == $ukuran->id){
                $response = $this->MemberModel->update($ukuran);
            }else{
                $response = [
					'msg'=>'no_telp must be unique',
					'error'=>true
				];
            }
		}else{
			$ukuran = new data();
			$ukuran->nama = $this->post('nama');
            $ukuran->no_telp = $this->post('no_telp');
            $ukuran->tanggal = $this->post('tanggal');
            $ukuran->alamat = $this->post('alamat');
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
            $response = $this->MemberModel->store($ukuran);
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
		$response = $this->MemberModel->delete($ukuran);
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
    public $tanggal;
    public $alamat;
	public $created_by;
	public $updaetd_by;
}
