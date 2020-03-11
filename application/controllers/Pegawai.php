<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Pegawai extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('RolePegawaiModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->PegawaiModel->get();
            foreach($response as $r){
                $r->id_role_pegawai = $this->RolePegawaiModel->searchForeign($r->id_role_pegawai)->keterangan;
            }
			return $this->returnData($response, false);
        }else{
            $response = $this->PegawaiModel->search($id);
            foreach($response as $r){
                $r->id_role_pegawai = $this->RolePegawaiModel->searchForeign($r->id_role_pegawai)->keterangan;
            }
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->PegawaiModel->rules();
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
					'field' => 'username',
					'label' => 'username',
					'rules' => 'is_unique[pegawai.username]'
				]
			);
		}
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->id = $id;
        $data->nama = $this->post('nama');
        $data->id_role_pegawai = $this->post('id_role_pegawai');
        $data->tanggal_lahir = date('Y-m-d',strtotime($this->post('tanggal_lahir')));
		$data->alamat = $this->post('alamat');
		$data->no_telp = $this->post('no_telp');
		$data->username = $this->post('username');
		$data->password = password_hash($this->post('password'),PASSWORD_BCRYPT);
		if($id != null){
			if($this->PegawaiModel->getByUsername($this->post('username')!=null) || $this->PegawaiModel->getByUsername($this->post('username')) == $data->id){
				$data->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
				$response = $this->PegawaiModel->update($data);
			}else{
				$response = [
					'msg'=>'Username must be unique',
					'error'=>true
				];
			}
		}else{
			$data->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			$response = $this->PegawaiModel->store($data);
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
		$response = $this->PegawaiModel->delete($ukuran);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function login_get(){
		$data = new data();
        $data->id = $id;
		$data->username = $this->post('username');
		$data->password = $this->post('password');
		$response = $this->PegawaiModel->login($data->username);
		if($response != null){
			if(!password_verify($data->password,$response->password)){
				$response = [
					'msg'=>'Username dan Password tidak cocok',
					'error'=>true
				];
			}else{
				$data = array('username'=>$response->username,'id_role_pegawai'=>$response->id_role_pegawai);
				$response = [
					'msg'=> $data,
					'error'=>false
				];
			}
			return $this->returnData($response['msg'], $response['error']);
		}else{
			$response = [
				'msg'=>'Username tidak ditemukan',
				'error'=>true
			];
		}
		
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
    public $id_role_pegawai;
    public $nama;
    public $tanggal_lahir;
    public $alamat;
    public $no_telp;
    public $username;
    public $password;
    public $updated_by;
    public $created_by;
}