<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Produk extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('ProdukModel');
		$this->load->model('DetilTransaksiProdukModel');
		$this->load->library('form_validation');
	}

	public function index_get($id_prd = null)
	{
        if($id_prd==null){
            $response = $this->ProdukModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->ProdukModel->search($id_prd);
			return $this->returnData($response,false);
		}
	}

	public function mobile_get($id_prd = null)
	{
        if($id_prd==null){
            $response = $this->ProdukModel->get();
			return $this->response($response);
        }else{
            $response = $this->ProdukModel->search($id_prd);
			return $this->response($response);
		}
	}

	public function mobileBest_get()
	{
		$bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $date=$bulan.'-'.$tahun;
		$response = $this->DetilTransaksiProdukModel->getTotalLaporanMobile($date);
		return $this->response($response);
	}

	public function byString_get($nama_prd = null){
		$response = $this->ProdukModel->searchByString($nama_prd);
		return $this->returnData($response, false);
	}

	public function stock_get($id_prd = null){
		if($id_prd == null){
			return $this->returnData('Parameter id_prd Tidak Ditemukan', true);
		}
		$jumlah = (int)$this->ProdukModel->getJumlahProduk($id_prd) - (int)$this->DetilTransaksiPenjualanModel->getJumlahDibeli($id_prd);
		return $this->returnData($jumlah, false);
		
	}

	public function index_post($id_prd = null)
	{
		$validation = $this->form_validation;
		$rule = $this->ProdukModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->id_prd = $id_prd;
        $data->nama_prd = $this->post('nama_prd');
        $data->deskripsi_prd = $this->post('deskripsi_prd');
        $data->harga_prd = $this->post('harga_prd');
		$data->satuan_prd = $this->post('satuan_prd');
		$data->stok_prd = $this->post('stok_prd');
		$data->ukuran_prd = $this->post('ukuran_prd');
		if($id_prd != null){
			$response = $this->ProdukModel->update($data);
		}else{
			$response = $this->ProdukModel->store($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function delete_post($id_prd=null){
		if($id_prd == null){
			return $this->returnData('Parameter id_prd Tidak Ditemukan', true);
		}
		$ukuran = new data();
		$ukuran->id_prd = $id_prd;
		$response = $this->ProdukModel->delete($ukuran);
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
	public $id_prd;
    public $nama_prd;
    public $deskripsi_prd;
    public $harga_prd;
    public $satuan_prd;
	public $stok_prd;
	public $ukuran_prd;
}