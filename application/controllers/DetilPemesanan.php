<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilPemesanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('DetilPemesananModel');
		$this->load->model('PegawaiModel');
        $this->load->model('ProdukModel');
        $this->load->model('PemesananModel');
		$this->load->library('form_validation');
	}

	public function index_get($idTransaksi = null)
	{
        if($idTransaksi==null){
			return $this->returnData('Parameter Id Transaksi Tidak Ditemukan', true);
        }else{
			$response = $this->DetilPemesananModel->get($idTransaksi);
			foreach($response as $r){
                $r->id_produk = $this->ProdukModel->searchForeign($r->id_produk)->nama;
            }
			return $this->returnData($response,false);
		}
    }
    
    public function detail_get($id = null)
	{
        if($id==null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }else{
			$response = $this->DetilPemesananModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilPemesananModel->rules();
		if($id != null){
			array_push(
				$rule,
				[
					'field' => 'pegawai',
					'label' => 'pegawai',
					'rules' => 'required'
				]
			);
		}else{
			array_push(
				$rule,
				[
					'field' => 'pegawai',
					'label' => 'pegawai',
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
            $ukuran->id_produk = $this->post('id_produk');
            $ukuran->id_pemesanan = $this->post('id_pemesanan');
            $ukuran->jumlah = $this->post('jumlah');
            $ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('pegawai'));
			$response = $this->DetilPemesananModel->update($ukuran);
		}else{
			$ukuran = new data();
			$ukuran->id_produk = $this->post('id_produk');
            $ukuran->id_pemesanan = $this->post('id_pemesanan');
            $ukuran->jumlah = $this->post('jumlah');
            $ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('pegawai'));
            $response = $this->DetilPemesananModel->store($ukuran);
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
		$response = $this->DetilPemesananModel->delete($ukuran);
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
    public $id_pemesanan;
    public $id_produk;
    public $jumlah;
	public $created_by;
	public $updaetd_by;
}
