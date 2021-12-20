<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Customer extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('CustomerModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->CustomerModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->CustomerModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function mobile_get()
	{
		$id = $_GET['kode'];
		$response = $this->CustomerModel->search($id);
		return $this->response($response);
		
	}


	public function registercount_get(){
		$response = $this->CustomerModel->getRegisteredByYear();
		return $this->returnData($response,false);
	}

	public function byString_get($nama = null){
		$response = $this->CustomerModel->searchByString($nama);
		foreach($response as $r){
			$r->nama_cust = $this->RoleModel->search($r->nama_cust)->nama_role;
		}
		return $this->returnData($response, false);
	}

	public function byUsername_get($username = null){
		if($username == null){
			return $this->returnData('Parameter Username Tidak Ditemukan', true);
		}
		$response = $this->CustomerModel->getByUsername($username);
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
		$rule = $this->CustomerModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->nama_cust = $this->post('nama_cust');
        $data->alamat_cust = $this->post('alamat_cust');
        $data->tgl_lahir_cust = date('Y-m-d',strtotime($this->post('tgl_lahir_cust')));
		$data->jk_cust = $this->post('jk_cust');
		$data->no_telp_cust = $this->post('no_telp_cust');
		$data->email_cust = $this->post('email_cust');
        $data->alergi_obat_cust = $this->post('alergi_obat_cust');
        $data->tgl_registrasi_cust = date('Y-m-d');
		if($id != null){
            $data->kode_cust = $id;
			$data->password_cust = $this->post('password_cust');
			if( $data->password_cust != 'null'){
				$data->password_cust = password_hash($data->password_cust,PASSWORD_BCRYPT);
				$response = $this->CustomerModel->updatePass($data);
			}
			$response = $this->CustomerModel->update($data);
		}else{
            $total = $this->CustomerModel->getCount();
            $dateRegis = date('my',strtotime($data->tgl_registrasi_cust));
            $dateBirth = date('dmY',strtotime($data->tgl_lahir_cust));
            $data->kode_cust = $dateRegis.$dateBirth.$total;
			$data->password_cust = password_hash($data->tgl_lahir_custs,PASSWORD_BCRYPT);
			$response = $this->CustomerModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function login_post(){
		$data = new data();
		$data->kode_cust = $this->post('kode_cust');
		$data->password_cust = $this->post('password_cust');
		$response = $this->CustomerModel->login($data->kode_cust);
		if($response != null){
			if(!password_verify($data->password_cust,$response->password_cust)){
				$data = array('kode_cust'=>null);
				return $this->response(null);
			}else{
				$response = $this->CustomerModel->search($data->kode_cust);
				return $this->response($response);
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
	public $kode_cust;
    public $nama_cust;
    public $alamat_cust;
    public $tgl_lahir_cust;
    public $jk_cust;
    public $no_telp_cust;
    public $email_cust;
    public $alergi_obat_cust;
    public $poin_cust;
    public $tgl_registrasi_cust;
    public $password_cust;
}