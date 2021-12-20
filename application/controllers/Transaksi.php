<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Transaksi extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('TransaksiModel');
        $this->load->model('JadwalModel');
        $this->load->model('PenanggungJawabModel');
		$this->load->model('ProdukModel');
		$this->load->model('CustomerModel');
		$this->load->model('DetilTransaksiProdukModel');
		$this->load->model('RuanganModel');
		$this->load->model('PegawaiModel');
		$this->load->model('DetilRuanganTransaksiModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->TransaksiModel->get();
			return $this->returnData($response, false);
        }else{
            $response = $this->TransaksiModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function mobile_get($id = null)
	{
		$id=$_GET['kode'];
		$response = $this->TransaksiModel->getMobile($id);
		return $this->response($response);
	}
	
	public function pendapatanByYear_get(){
		$tahun = $_GET['tahun'];
		$response = $this->TransaksiModel->getPendapatanByYear($tahun);
		return $this->returnData($response,false);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->TransaksiModel->rules();
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $data = new data();
        $data->kode_cust = $this->post('kode_cust');
        $data->tgl_transaksi = date('Y-m-d H:i:s');
        $data->id_pegawai = $this->post('id_pegawai');
        $data->id_jadwal = $this->JadwalModel->getCurrent();
		if($id != null){
            $data->kode_cust = $id;
            $response = $this->TransaksiModel->update($data);
		}else{
            $total = $this->TransaksiModel->getCount();
            $dateRegis = date('dmy',strtotime($data->tgl_transaksi));
            $data->kode_transaksi = $dateRegis.'-'.$total;
			$response = $this->TransaksiModel->store($data);
            if($response['error'] == false){
                $response = $this->PenanggungJawabModel->store($data);
				$response['msg'] = $data->kode_transaksi;
            }
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function cashier_get(){
		$response = $this->TransaksiModel->getByStatus('cashier');
		foreach($response as $r){
			$r->kode_cust = $this->CustomerModel->search($r->kode_cust)->nama_cust;
		}
		return $this->returnData($response, false);
	}

	public function dokter_get($id = null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$response = $this->TransaksiModel->getByDokter($id);
		foreach($response as $r){
			$r->kode_cust = $this->CustomerModel->search($r->kode_cust)->nama_cust;
		}
		return $this->returnData($response, false);
	}

	public function beautician_get($id = null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$response = $this->TransaksiModel->getByBeautician($id);
		return $this->returnData($response, false);
	}

	public function keluhan_get($id = null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$response = $this->TransaksiModel->getKeluhanCust($id);
		return $this->returnData($response, false);
	}

    public function keluhan_post($id = null)
	{
        $data = new data();
        $data->keluhan = $this->post('keluhan');
        $data->kode_transaksi = $id;
        $response = $this->TransaksiModel->updateKeluhan($data);
		return $this->returnData($response['msg'], $response['error']);
	}


    public function delete_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$produk = $this->DetilTransaksiProdukModel->get($id);
		foreach($produk as $r){
			$response = $this->ProdukModel->search($r->id_produk);
            $response->stok_prd = $response->stok_prd + $r->jumlah_prd;
            $response = $this->ProdukModel->updateStock($response);
		}
		$ruangan = $this->DetilRuanganTransaksiModel->getByTransaksi($id);
		if ($ruangan!=null) {
			$response = $this->RuanganModel->setAvailable($ruangan->no_ruangan);
		}
		$response = $this->TransaksiModel->delete($data);
		return $this->returnData($response['msg'], $response['error']);
	}

    public function doctor_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id; 
		$data->id_pegawai = $this->post('id_pegawai');
		$response = $this->PenanggungJawabModel->store($data);
		if($response['error']==false){
			$response = $this->TransaksiModel->updateDoctor($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

    public function cashier_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$response = $this->TransaksiModel->updateCashier($data);
		return $this->returnData($response['msg'], $response['error']);
	}

    public function beautician_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$data->id_pegawai = $this->post('id_pegawai');
		$response = $this->PenanggungJawabModel->store($data);
		if($response['error']==false){
			$response = $this->TransaksiModel->updateBeautician($data);
		}
		return $this->returnData($response['msg'], $response['error']);
	}

	public function beauticianCS_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$data->id_pegawai = $this->post('id_pegawai');
		$response = $this->PenanggungJawabModel->store($data);
		if($response['error']==false){
			$response = $this->RuanganModel->setUnavailable($this->post('no_ruangan'));
			if($response['error']==false){
				$response = $this->DetilRuanganTransaksiModel->store($this->post('no_ruangan'),$id);
				if($response['error']==false){
					$response = $this->PegawaiModel->setUnavailable($this->post('id_pegawai'));
					if($response['error']==false){
						$response = $this->TransaksiModel->updateCashier($data);
					}
				}
			}
		}
		return $this->returnData($response['msg'], $response['error']);
	}


	public function done_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$response = $this->RuanganModel->setAvailable($this->post('no_ruangan'));
		if($response['error']==false){
			$response = $this->PegawaiModel->setAvailable($this->post('id_pegawai'));
			if($response['error']==false){
				$response = $this->TransaksiModel->updateSelesai($data);
			}
		}
		return $this->returnData($response['msg'], $response['error']);
	}


	public function pay_post($id=null){
		if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
		}
		$data = new data();
		$data->kode_transaksi = $id;
		$data->kode_promo = $this->post('kode_promo');
		$data->total = $this->post('total');
		$data->id_pegawai = $this->post('id_pegawai');
		$response = $this->PenanggungJawabModel->store($data);
		if($response['error']==false){
			$transaksi = $this->TransaksiModel->search($data->kode_transaksi);
			$customer = $this->CustomerModel->search($transaksi->kode_cust);
			$customer->poin_cust = $customer->poin_cust+$this->post('poin');
			$response = $this->CustomerModel->updatePoin($customer);
			if($response['error']==false){
				$response = $this->TransaksiModel->promo($data);
				if($response['error']==false){
					if($this->post('isDone')==1){
						$response = $this->TransaksiModel->updateSelesai($data);
					}else{
						$response = $this->TransaksiModel->updateBeautician($data);
					}	
				}
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
	public $kode_transaksi;
    public $kode_cust;
    public $id_jadwal;
    public $id_pegawai;
    public $kode_promo;
    public $tgl_transaksi;
    public $total;
    public $keluhan;
    public $status_transaksi;
	public $poin;
}