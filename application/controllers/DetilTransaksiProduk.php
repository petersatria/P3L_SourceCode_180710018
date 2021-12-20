<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilTransaksiProduk extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('DetilTransaksiProdukModel');
        $this->load->model('ProdukModel');
        $this->load->model('TransaksiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        $response = $this->DetilTransaksiProdukModel->get($id);
        return $this->returnData($response,false);
	}

    public function totalLaporan_get(){
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $tgl=$bulan.'-'.$tahun;
        $response = $this->DetilTransaksiProdukModel->getTotalLaporan($tgl);
        return $this->returnData($response,false);
    }

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilTransaksiProdukModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->kode_transaksi = $this->post('kode_transaksi');
        $data->id_produk = $this->post('id_produk');
        $data->jumlah_prd = $this->post('jumlah_prd');
        $data->sub_total_prd = $this->post('sub_total_prd');
        $id = $this->DetilTransaksiProdukModel->getId($data);
		if($id == null){
            $response = $this->ProdukModel->search($data->id_produk);
            $response->stok_prd = $response->stok_prd - $data->jumlah_prd;
            if($response->stok_prd < 0){
                return $this->returnData('Stok Tidak Cukup', true);
            }
            $response = $this->ProdukModel->updateStock($response);
            if($response['error']==false){
                $response = $this->TransaksiModel->search($data->kode_transaksi);
                $response->total = $response->total + $data->sub_total_prd;
                $response = $this->TransaksiModel->updateTotal($response);
                if($response['error']==false){
                    $response = $this->DetilTransaksiProdukModel->store($data);
                }
            }
		}else{
            $data->id_detil_transaksi_produk = $id;
            $response = $this->ProdukModel->search($data->id_produk);
            $oldData = $this->DetilTransaksiProdukModel->search($data->id_detil_transaksi_produk);
            $response->stok_prd = $response->stok_prd - $data->jumlah_prd + $oldData->jumlah_prd;
            if($response->stok_prd < 0){
                return $this->returnData('Stok Tidak Cukup', true);
            }
            $response = $this->ProdukModel->updateStock($response);
            if($response['error']==false){
                $response = $this->TransaksiModel->search($data->kode_transaksi);
                $response->total = $response->total - $oldData->sub_total_prd + $data->sub_total_prd;
                $response = $this->TransaksiModel->updateTotal($response);
                if($response['error']==false){
                    $response = $this->DetilTransaksiProdukModel->update($data);
                }
            }
        }
		return $this->returnData($response['msg'], $response['error']);
	}


    public function delete_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->id_detil_transaksi_produk = $id;
        $oldData = $this->DetilTransaksiProdukModel->search($data->id_detil_transaksi_produk);
        $response = $this->TransaksiModel->search($oldData->kode_transaksi);
        $response->total = $response->total - $oldData->sub_total_prd;
        $response = $this->TransaksiModel->updateTotal($response);
        if($response['error']==false){
            $response = $this->ProdukModel->search($oldData->id_produk);
            $response->stok_prd = $response->stok_prd + $oldData->jumlah_prd;
            $response = $this->ProdukModel->updateStock($response);
            if($response['error']==false){
                $response = $this->DetilTransaksiProdukModel->delete($data->id_detil_transaksi_produk);
            }
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
	public $id_detil_transaksi_produk;
    public $kode_transaksi;
    public $id_produk;
    public $jumlah_prd;
    public $sub_total_prd;
    public $total;
}