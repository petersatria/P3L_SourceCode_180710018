<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilTransaksiPenjualan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('DetilTransaksiPenjualanModel');
		$this->load->model('PegawaiModel');
        $this->load->model('ProdukModel');
        $this->load->model('TransaksiPenjualanModel');
		$this->load->library('form_validation');
	}

	public function index_get($idTransaksi = null)
	{
        if($idTransaksi==null){
			return $this->returnData('Parameter Id Transaksi Tidak Ditemukan', true);
        }else{
			$response = $this->DetilTransaksiPenjualanModel->get($idTransaksi);
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
			$response = $this->DetilTransaksiPenjualanModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function getJumlah_get($id_transaksi = null , $id_produk = null){
		if($id_transaksi==null||$id_produk==null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }else{
			$response = $this->DetilTransaksiPenjualanModel->getJumlah($id_transaksi,$id_produk);
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilTransaksiPenjualanModel->rules();
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
        $id = $this->DetilTransaksiPenjualanModel->getId($this->post('id_transaksi'),$this->post('id_produk'));
		if($id != null){
			$ukuran = new data();
			$ukuran->id = $id;
            $ukuran->id_produk = $this->post('id_produk');
            $ukuran->id_transaksi = $this->post('id_transaksi');
            $ukuran->harga = $this->post('harga');
            $ukuran->jumlah = $this->post('jumlah');
			$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('pegawai'));
			if((int)$this->ProdukModel->getJumlahProduk($ukuran->id_produk) - (int)$ukuran->jumlah - (int)$this->DetilTransaksiPenjualanModel->getJumlahDibeli($ukuran->id_produk) + (int)$this->DetilTransaksiPenjualanModel->getJumlahById($ukuran->id) < 0){
                $response = [
                    'msg' => 'Jumlah Tidak Tersedia',
                    'error' => true
                ];
            }else{
                $response = $this->DetilTransaksiPenjualanModel->update($ukuran);
            }
		}else{
			$ukuran = new data();
			$ukuran->id_produk = $this->post('id_produk');
            $ukuran->id_transaksi = $this->post('id_transaksi');
            $ukuran->harga = $this->post('harga');
            $ukuran->jumlah = $this->post('jumlah');
            $ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('pegawai'));
            if((int)$this->ProdukModel->getJumlahProduk($ukuran->id_produk) - (int)$ukuran->jumlah - (int)$this->DetilTransaksiPenjualanModel->getJumlahDibeli($ukuran->id_produk) < 0){
                $response = [
                    'msg' => 'Jumlah Tidak Tersedia',
                    'error' => true
                ];
            }else{
                $response = $this->DetilTransaksiPenjualanModel->store($ukuran);
            }
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
		$response = $this->DetilTransaksiPenjualanModel->delete($ukuran);
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
    public $id_produk;
    public $id_transaksi;
    public $harga;
    public $jumlah;
	public $created_by;
	public $updaetd_by;
}
