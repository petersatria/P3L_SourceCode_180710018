<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class PembayaranPenjualan extends REST_Controller
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
        $this->load->model('PembayaranPenjualanModel');
		$this->load->library('form_validation');
	}

	public function index_get($idTransaksi = null)
	{
        if($idTransaksi==null){
			return $this->returnData('Parameter Id Transaksi Tidak Ditemukan', true);
        }else{
			$response = $this->PembayaranPenjualanModel->get($idTransaksi);
			return $this->returnData($response,false);
		}
    }

	public function index_post()
	{
		$validation = $this->form_validation;
		$rule = $this->PembayaranPenjualanModel->rules();
        array_push(
            $rule,
            [
                'field' => 'created_by',
                'label' => 'craeted_by',
                'rules' => 'required'
            ]
        );
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $ukuran = new data();
        $ukuran->id_transaksi = $this->post('id_transaksi');
        $ukuran->diskon = $this->post('diskon');
        $ukuran->bayar = $this->post('bayar');
        $ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
        $response = $this->PembayaranPenjualanModel->store($ukuran);
        if(!$response['error']){
            $response = $this->DetilTransaksiPenjualanModel->get($ukuran->id_transaksi);
            foreach($response as $r){
                $jmlh = $this->ProdukModel->getJumlahProduk($r->id_produk);
                $p = new dataProduk();
                $p->id = $r->id_produk;
                $p->jumlah = (int)$jmlh - (int)$r->jumlah;
                $p->updated_by = $ukuran->created_by;
                $response = $this->ProdukModel->updateStock($p);
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
	public $id;
    public $id_transaksi;
    public $diskon;
    public $bayar;
	public $created_by;
	public $updaetd_by;
}

class dataProduk
{
    public $id;
    public $jumlah;
	public $updaetd_by;
}