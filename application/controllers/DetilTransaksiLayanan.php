<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class DetilTransaksiLayanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('DetilTransaksiLayananModel');
		$this->load->model('PegawaiModel');
        $this->load->model('LayananModel');
        $this->load->model('JenisLayananModel');
        $this->load->model('UkuranHewanModel');
        $this->load->model('HewanModel');
        $this->load->model('TransaksiPenjualanModel');
		$this->load->library('form_validation');
	}

	public function index_get($idTransaksi = null)
	{
        if($idTransaksi==null){
			return $this->returnData('Parameter Id Transaksi Tidak Ditemukan', true);
        }else{
			$response = $this->DetilTransaksiLayananModel->get($idTransaksi);
			foreach($response as $r){
                $id_ukuran_hewan = $this->LayananModel->searchForeign($r->id_layanan)->id_ukuran_hewan;
                $jenis_layanan = $this->LayananModel->searchForeign($r->id_layanan)->id_layanan;
                $nama_uh = $this->UkuranHewanModel->searchForeign($id_ukuran_hewan)->nama;
                $nama_jl = $this->JenisLayananModel->searchForeign($jenis_layanan)->nama;
                $r->id_layanan = $nama_jl.' '.$nama_uh;
                $r->id_hewan = $this->HewanModel->searchForeign($r->id_hewan)->nama;
            }
			return $this->returnData($response,false);
		}
    }

    public function detail_get($id = null)
	{
        if($id==null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }else{
			$response = $this->DetilTransaksiLayananModel->search($id);
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->DetilTransaksiLayananModel->rules();
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
				]
			);
		}
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
		if($id != null){
            $hewan = new dataHewan();
            $hewan->id = $this->DetilTransaksiLayananModel->getIdHewanById($id);
            $hewan->nama = $this->post('nama_hewan');
            $hewan->id_jenis_hewan = $this->post('id_jenis_hewan');
            $hewan->tanggal_lahir = $this->post('tanggal_lahir');
            $hewan->updated_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
            $response = $this->HewanModel->update($hewan);
            if(!$response['error']){
                $ukuran = new data();
                $ukuran->id = $id;
                $ukuran->id_transaksi = $this->post('id_transaksi');
                $ukuran->id_layanan = $this->post('id_layanan');
                $ukuran->id_hewan = $response['msg'];
                $ukuran->harga = $this->post('harga');
                $ukuran->jumlah = $this->post('jumlah');
                $ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
                $response = $this->DetilTransaksiLayananModel->update($ukuran);
            }
		}else{
            $hewan = new dataHewan();
            $hewan->nama = $this->post('nama_hewan');
            $hewan->id_jenis_hewan = $this->post('id_jenis_hewan');
            $hewan->tanggal_lahir = $this->post('tanggal_lahir');
            $hewan->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
            $response = $this->HewanModel->store($hewan);
            if(!$response['error']){
                $ukuran = new data();
                $ukuran->id_transaksi = $this->post('id_transaksi');
                $ukuran->id_layanan = $this->post('id_layanan');
                $ukuran->id_hewan = $response['msg'];
                $ukuran->harga = $this->post('harga');
                $ukuran->jumlah = $this->post('jumlah');
                $ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
                $response = $this->DetilTransaksiLayananModel->store($ukuran);
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
        $hewan = new dataHewan();
        $hewan->id = $this->DetilTransaksiLayananModel->getIdHewanById($id);
        $ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
        $ukuran->id = $id;
        $response = $this->DetilTransaksiLayananModel->delete($ukuran);
        if(!$response['error']){
            $response = $this->HewanModel->hardDelete($hewan);
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
	public $id;
    public $id_layanan;
    public $id_hewan;
    public $harga;
    public $jumlah;
    public $id_transaksi;
	public $created_by;
	public $updaetd_by;
}

class dataHewan
{
    public $id;
    public $nama;
    public $id_jenis_hewan;
    public $tanggal_lahir;
	public $created_by;
	public $updated_by;
}
