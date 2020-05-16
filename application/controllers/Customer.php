<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Customer extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('CustomerModel');
	}

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Customer/produk?order_by=harga&isDesc=TRUE
    //order_by: {nama, kategori, harga, jumlah}
    //isDesc: {TRUE, FALSE}

    //Search: http://localhost/rest_api-kouvee-pet-shop/index.php/Customer/produk/12
	public function produk_get($id = null)
	{
        if($id==null){
            $response = $this->CustomerModel->get_produk($_GET['order_by'],$_GET['isDesc']);
			return $this->returnData($response, FALSE);
        }else{
            $response = $this->CustomerModel->search_produk($id);
            if ($response != null) {
                return $this->returnData($response,FALSE);
            }
            else {
                return $this->returnData("Produk TIdak Ditemukan",TRUE);
            }
		}
    }
    

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Customer/layanan?order_by=harga&isDesc=TRUE
    //order_by: {nama, harga}
    //isDesc: {TRUE, FALSE}

    //Search: http://localhost/rest_api-kouvee-pet-shop/index.php/Customer/layanan/12
    public function layanan_get($id = null)
	{
        if($id==null){
            $response = $this->CustomerModel->get_layanan($_GET['order_by'],$_GET['isDesc']);
			return $this->returnData($response, FALSE);
        }else{
            $response = $this->CustomerModel->search_layanan($id);
            if ($response != null) {
                return $this->returnData($response,FALSE);
            }
            else {
                return $this->returnData("Layanan TIdak Ditemukan",TRUE);
            }
		}
	}
	
	public function returnData($msg, $error)
	{
		$response['error'] = $error;
		$response['message'] = $msg;
		return $this->response($response);
	}
}