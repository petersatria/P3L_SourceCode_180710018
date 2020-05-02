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

        $cek_stok = $this->searchProdukHabis();
        if ($cek_stok != "null") {
            echo $this->notification($cek_stok);
        }

		return $this->returnData($response['msg'], $response['error']);
    }

    public function searchProdukHabis(){
		$produk =  $this->ProdukModel->searchProdukHabis();
		if (empty($produk[2]->nama) == FALSE) {
			$result = $produk[0]->nama.', '.$produk[1]->nama.' dan '.$produk[2]->nama;
		}
		else if (empty($produk[1]->nama) == FALSE) {
			$result = $produk[0]->nama.' dan '.$produk[1]->nama;
		}
		else if (empty($produk[0]->nama) == FALSE){
			$result = $produk[0]->nama;
		}
		else {
			$result = "null";
		}
		return $result;
    }
    
    public function notification($produk) {
		define('API_ACCESS_KEY','AAAAT0fDIig:APA91bEN2Rma-dQRiRLMFjoH2v7PdNors_FA6kl1czo6wsd1NYTB2sh1F9xmvmq6uQgpfJXPcfVC7MpcrXmxYQHzYgOPZe3vz910T3SNKiq9II_srhZaGe5k7S4cXvUgSVFvfSa_ZDYn');
		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
		$token='e6SoLoRjTRCwBfhHwc6G9m:APA91bHmBG5gi-wTXY_xLIr-taA3uJyfU-rH5Tw-l_nRS3PGax6ntxb_CTKUT-SEFIdnMcPp5UosvlFLOMuZw6nf50RdVx2ng8qPtqUhO69YIYaxE0uRLhRWqHwtYPDpcSB7GJjyV-f7';

    	$notification = [
            'title' =>'Stok Barang Menipis',
            'body' => $produk
        ];
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $token, //single token
            'notification' => $notification,
			'data' => $extraNotificationData,
        ];

        $headers = [
			'Content-Type: application/json',
            'Authorization: key=' . API_ACCESS_KEY
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        return $result;
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