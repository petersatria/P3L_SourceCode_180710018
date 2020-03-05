<?php

use Restserver\Libraries\REST_Controller;

class Produk extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->model('KategoriProdukModel');
		$this->load->library('form_validation');
	}

	public function index_get($id = null)
	{
        if($id==null){
            $response = $this->ProdukModel->get();
            foreach($response as $r){
                $r->id_kategori_produk = $this->KategoriProdukModel->search($r->id_kategori_produk)->keterangan;
            }
			return $this->returnData($response, false);
        }else{
            $response = $this->ProdukModel->search($id);
            foreach($response as $r){
                $r->id_kategori_produk = $this->KategoriProdukModel->search($r->id_kategori_produk)->keterangan;
            }
			return $this->returnData($response,false);
		}
	}

	public function index_post($id = null)
	{
		$validation = $this->form_validation;
		$rule = $this->ProdukModel->rules();
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
        $data = new data();
        $data->id = $id;
        $data->nama = $this->post('nama');
        $data->id_kategori_produk = $this->post('id_kategori_produk');
        $data->harga = $this->post('harga');
		$data->satuan = $this->post('satuan');
		$data->jmlh_min = $this->post('jmlh_min');
        $data->jmlh = $this->post('jmlh');
		if($id != null){
            $data->updated_by = $this->post('updated_by');
			$response = $this->ProdukModel->update($data);
		}else{
			$data->created_by = $this->post('created_by');
			$response = $this->ProdukModel->store($data);
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
		$ukuran->updated_by = $this->post('updated_by');
		$ukuran->id = $id;
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
	public $id;
    public $nama;
    public $id_kateogri_produk;
    public $harga;
    public $satuan;
    public $jmlh_min;
    public $jmlh;
	public $created_by;
	public $updaetd_by;
}