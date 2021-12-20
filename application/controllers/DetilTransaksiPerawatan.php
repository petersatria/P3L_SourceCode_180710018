<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilTransaksiPerawatan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('DetilTransaksiPerawatanModel');
        $this->load->model('PerawatanModel');
        $this->load->model('TransaksiModel');
        $this->load->model('CustomerModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        $response = $this->DetilTransaksiPerawatanModel->get($id);
        return $this->returnData($response,false);
	}

    public function totalLaporan_get(){
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $tgl=$bulan.'-'.$tahun;
        $response = $this->DetilTransaksiPerawatanModel->getTotalLaporan($tgl);
        return $this->returnData($response,false);
    }

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilTransaksiPerawatanModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->kode_transaksi = $this->post('kode_transaksi');
        $data->id_perawatan = $this->post('id_perawatan');
        $data->jumlah_prw = $this->post('jumlah_prw');
        $data->sub_total_prw = $this->post('sub_total_prw');
        $id = $this->DetilTransaksiPerawatanModel->getId($data);
		if($id == null){
            $response = $this->TransaksiModel->search($data->kode_transaksi);
            $response->total = $response->total + $data->sub_total_prw;
            $response = $this->TransaksiModel->updateTotal($response);
            if($response['error']==false){
                $response = $this->DetilTransaksiPerawatanModel->store($data);
            }
		}else{
            $data->id_detil_transaksi_perawatan = $id;
            $response = $this->TransaksiModel->search($data->kode_transaksi);
            $oldData = $this->DetilTransaksiPerawatanModel->search($data->id_detil_transaksi_perawatan);
            $response->total = $response->total - $oldData->sub_total_prw + $data->sub_total_prw;
            $response = $this->TransaksiModel->updateTotal($response);
            if($response['error']==false){
                $response = $this->DetilTransaksiPerawatanModel->update($data);
            }
        }
		return $this->returnData($response['msg'], $response['error']);
	}

    public function usedPoin_post($id = null)
	{
        $data = new data();
        $data->kode_transaksi = $this->post('kode_transaksi');
        $data->id_detil_transaksi_perawatan = $id;
        $data->poin = $this->post('poin');

        $transaksi = $this->TransaksiModel->search($data->kode_transaksi);
        $customer = $this->CustomerModel->search($transaksi->kode_cust);
        $oldData = $this->DetilTransaksiPerawatanModel->search($data->id_detil_transaksi_perawatan);

        $customer->poin_cust = $customer->poin_cust-$data->poin;
        
        $response = $this->CustomerModel->updatePoin($customer);
        if($response['error'] == false){
            $transaksi->total = $transaksi->total - $oldData->sub_total_prw;
            $response = $this->TransaksiModel->updateTotal($transaksi);
            if($response['error']==false){
                $response = $this->DetilTransaksiPerawatanModel->setUsedPoin($data);
            }
        }
		return $this->returnData($response['msg'], $response['error']);
	}


    public function delete_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->id_detil_transaksi_perawatan = $id;
        $oldData = $this->DetilTransaksiPerawatanModel->search($data->id_detil_transaksi_perawatan);
        $response = $this->TransaksiModel->search($oldData->kode_transaksi);
        $response->total = $response->total - $oldData->sub_total_prw;
        $response = $this->TransaksiModel->updateTotal($response);
        if($response['error']==false){
            $response = $this->DetilTransaksiPerawatanModel->delete($data->id_detil_transaksi_perawatan);
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
	public $id_detil_transaksi_perawatan;
    public $kode_transaksi;
    public $id_perawatan;
    public $jumlah_prw;
    public $sub_total_prw;
    public $total;
    public $poin;
}