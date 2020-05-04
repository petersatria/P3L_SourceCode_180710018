<?php


use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class TransaksiLayanan extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('TransaksiLayananModel');
		$this->load->model('PegawaiModel');
		$this->load->model('MemberModel');
		$this->load->model('PembayaranLayananModel');
		$this->load->library('form_validation');
	}

	public function admin_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiLayananModel->getForAdmin();
			foreach($response as $r){
                if($r->id_cashier != null){
                    $r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
                }
                $r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
            }
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiLayananModel->searchForAdmin($id);
			if($response->id_cashier != null){
				$response->id_cashier = $this->PegawaiModel->searchForeign($response->id_cashier)->nama;
			}
			$response->id_CS = $this->PegawaiModel->searchForeign($response->id_CS)->nama;
			return $this->returnData($response,false);
		}
	}

	public function cs_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiLayananModel->getForCS();
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiLayananModel->searchForCS($id);
			return $this->returnData($response,false);
		}
	}

	public function cashier_get($id = null)
	{
        if($id==null){
			$response = $this->TransaksiLayananModel->getForCashier();
			return $this->returnData($response, false);
        }else{
			$response = $this->TransaksiLayananModel->searchForCashier($id);
			return $this->returnData($response,false);
		}
	}

	public function adminByString_get($nama = null){
		$response = $this->TransaksiLayananModel->searchByStringAdmin($nama);
		foreach($response as $r){
			if($r->id_cashier != null){
				$r->id_cashier = $this->PegawaiModel->searchForeign($r->id_cashier)->nama;
			}
			if($r->id_CS != null){
				$r->id_CS = $this->PegawaiModel->searchForeign($r->id_CS)->nama;
			}
		}
		return $this->returnData($response, false);
	}

	public function cashierByString_get($nama = null){
		$response = $this->TransaksiLayananModel->searchByStringCashier($nama);
		return $this->returnData($response, false);
	}

	public function csByString_get($nama = null){
		$response = $this->TransaksiLayananModel->searchByStringCS($nama);
		return $this->returnData($response, false);
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->TransaksiLayananModel->rules();
		if($id == null){
			array_push(
				$rule,
				[
					'field' => 'created_by',
					'label' => 'craeted_by',
					'rules' => 'required'
				]
			);
		}else{
			array_push(
				$rule,
				[
					'field' => 'updated_by',
					'label' => 'updated_by',
					'rules' => 'required'
				]
			);
		}
		$validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
		}
		$ukuran = new data();
		if($id == null){
			$ukuran->is_member = $this->post('is_member');
			$ukuran->no_telp = $this->post('no_telp');
			$ukuran->id_CS = $this->PegawaiModel->getIdPegawai($this->post('id_CS'));
			$ukuran->status = 'belum selesai';
			$ukuran->created_by = $this->PegawaiModel->getIdPegawai($this->post('created_by'));
			if($ukuran->is_member == '0' && $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$ukuran->is_member = '1';
			}
			if($ukuran->is_member == '0' || $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
				$response = $this->TransaksiLayananModel->store($ukuran);
			}else{
				$response = [
					'msg' => 'Member dengan Nomor Telepon tidak tersedia',
					'error' => true 
				];
			}
		}else{
			if($this->PembayaranLayananModel->isPayed($id) != null){
				$ukuran->id = $id;
				$ukuran->is_member = $this->post('is_member');
				$ukuran->no_telp = $this->post('no_telp');
				$ukuran->id_CS = $this->PegawaiModel->getIdPegawai($this->post('id_CS'));
				$ukuran->status = 'belum selesai';
				$ukuran->updated_by = $this->PegawaiModel->getIdPegawai($this->post('updated_by'));
				if($ukuran->is_member == '0' && $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
					$ukuran->is_member = '1';
				}
				if($ukuran->is_member == '0' || $this->MemberModel->getIdMemberByTelp($ukuran->no_telp) != null){
					$response = $this->TransaksiLayananModel->update($ukuran);
				}else{
					$response = [
						'msg' => 'Member dengan Nomor Telepon tidak tersedia',
						'error' => true 
					];
				}
			}else{
				$response = [
					'msg' => 'Transaksi sudah dibayar dan tidak bisa ubah',
					'error' => true 
				];
			}
			
		}
		
		return $this->returnData($response['msg'], $response['error']);
	}

	public function done_post($id=null){
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
		if($this->PembayaranLayananModel->isPayed($id) != null){
			$ukuran->status = 'lunas';
		}else{
			$ukuran->status = 'belum lunas';
		}
		$response = $this->TransaksiLayananModel->done($ukuran);

		return $this->returnData($response['msg'], $response['error']);
	}

	public function sms($layanan) {
		function SendAPI_SMS($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response  = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		
		$email_api    = urlencode("yosapat.nababan@yahoo.co.id");
		$passkey_api  = urlencode("Hm123123");
		$no_hp_tujuan = urlencode("082178156402");
		$isi_pesan    = urlencode("Layanan ".$layanan." Anda Sudah Selesai");
		
		$url          = "https://reguler.medansms.co.id/sms_api.php?action=kirim_sms&email=".$email_api."&passkey=".$passkey_api."&no_tujuan=".$no_hp_tujuan."&pesan=".$isi_pesan."&json=1";
		$result       = SendAPI_SMS($url);
		echo $result;
	}

	public function pay_post($id=null){
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
		if($this->TransaksiLayananModel->isDone($id) != null){
			$ukuran->status = 'lunas';
		}else{
			$ukuran->status = 'belum selesai';
		}
		$response = $this->TransaksiLayananModel->pay($ukuran);
		return $this->returnData($response['msg'], $response['error']);
	}

	public function cancel_post($id=null){
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
		$response = $this->TransaksiLayananModel->cancel($ukuran);
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
    public $no_transaksi;
    public $is_member;
    public $no_telp;
    public $id_cashier;
    public $id_CS;
    public $tgl_transaksi;
    public $status;
    public $isDelete;
	public $created_by;
	public $updaetd_by;
}
