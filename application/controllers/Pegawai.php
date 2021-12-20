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
		$this->load->model('RoleModel');
		$this->load->model('PegawaiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->PegawaiModel->get();
			foreach($response as $r){
				$r->id_role_pegawai = $this->RoleModel->search($r->id_role_pegawai)->nama_role;
			}
			return $this->returnData($response, false);
        }else{
            $response = $this->PegawaiModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function dokter_get($jadwal = null){
		if($jadwal == null){
			return $this->returnData('Parameter jadwal Tidak Ditemukan', true);
		}
		$response = $this->PegawaiModel->getDokter($jadwal);
		if($response !=null){
			$response = [
				'msg' => $response,
				'error' => false
			];
		}else{
			$response = [
				'msg'=>'jadwal tidak ditemukan',
				'error'=>true
			];
		}
		return $this->returnData($response['msg'],$response['error']);
	}

	public function beautician_post(){
		$data = new data();
        $data->id_jadwal = $this->post('id_jadwal');
        $data->jk_pegawai = $this->post('jk_pegawai');
		

		$response = $this->PegawaiModel->getBeautician($data);
		if($response !=null){
			$response = [
				'msg' => $response,
				'error' => false
			];
		}else{
			$response = [
				'msg'=>'jadwal tidak ditemukan',
				'error'=>true
			];
		}
		return $this->returnData($response['msg'],$response['error']);
	}


	public function byString_get($nama = null){
		$response = $this->PegawaiModel->searchByString($nama);
		foreach($response as $r){
			$r->id_role_pegawai = $this->RoleModel->search($r->id_role_pegawai)->nama_role;
		}
		return $this->returnData($response, false);
	}

	public function byUsername_get($username = null){
		if($username == null){
			return $this->returnData('Parameter Username Tidak Ditemukan', true);
		}
		$response = $this->PegawaiModel->getByUsername($username);
		if($response !=null){
			$response = [
				'msg' => $response,
				'error' => false
			];
		}else{
			$response = [
				'msg'=>'Username tidak ditemukan',
				'error'=>true
			];
		}
		return $this->returnData($response['msg'],$response['error']);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->PegawaiModel->rules();
        if($id == null){
            array_push(
				$rule,
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
        $data->id_pegawai = $id;
        $data->id_role_pegawai = $this->post('id_role_pegawai');
        $data->nama_pegawai = $this->post('nama_pegawai');
        $data->alamat_pegawai = $this->post('alamat_pegawai');
		$data->no_telp_pegawai = $this->post('no_telp_pegawai');
		$data->jk_pegawai = $this->post('jk_pegawai');
		$data->username = $this->post('username');
		if($id != null){

			$data->password = $this->post('password');
			if( $data->password != ''){
				$data->password = password_hash($data->password,PASSWORD_BCRYPT);
				$response = $this->PegawaiModel->updatePass($data);
			}

			if($this->PegawaiModel->getIdPegawai($this->post('username')) == null || $this->PegawaiModel->getIdPegawai($this->post('username')) == $data->id_pegawai){
				$response = $this->PegawaiModel->update($data);
			}else{
				$response = [
					'msg'=>'Username must be unique',
					'error'=>true
				];
			}
		}else{
			$data->password = password_hash($this->post('password'),PASSWORD_BCRYPT);
			$response = $this->PegawaiModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}


	public function delete_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->id_pegawai = $id;
		$response = $this->PegawaiModel->delete($data);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function login_post(){
		$data = new data();
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
				$response->id_role_pegawai = $this->RoleModel->search($response->id_role_pegawai)->nama_role;
				$data = array('username'=>$response->username,'id_role_pegawai'=>$response->id_role_pegawai,'id_pegawai'=>$response->id_pegawai);
				$response = [
					'msg'=> $data,
					'error'=>false
				];
			}
		}else{
			$response = [
				'msg'=>'Username tidak ditemukan',
				'error'=>true
			];
		}
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
	public $id_pegawai;
    public $id_role_pegawai;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $no_telp_pegawai;
    public $jk_pegawai;
    public $username;
    public $password;
    public $isAvailable;
	public $id_jadwal;
}