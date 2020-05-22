<?php

use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

class Log extends REST_Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
		$this->load->model('LogModel');
	}

	public function hewan_get()
	{
        $response = $this->LogModel->hewanGet();
        return $this->returnData($response,false);
    }

    public function hewanSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->hewanSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function hewanSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->hewanSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function hewanSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->hewanSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////

    public function jenisHewan_get()
	{
        $response = $this->LogModel->jenisHewanGet();
        return $this->returnData($response,false);
    }

    public function jenisHewanSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->jenisHewanSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function jenisHewanSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->jenisHewanSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function jenisHewanSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->jenisHewanSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    //////////////////////////////////////////////////////////////////////

    public function jenisLayanan_get()
	{
        $response = $this->LogModel->jenisLayananGet();
        return $this->returnData($response,false);
    }

    public function jenisLayananSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->jenisLayananSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function jenisLayananSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->jenisLayananSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function jenisLayananSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->jenisLayananSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    /////////////////////////////////////////////////////////////////////

    public function kategoriProduk_get()
	{
        $response = $this->LogModel->kategoriProdukGet();
        return $this->returnData($response,false);
    }

    public function kategoriProdukSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->kategoriProdukSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function kategoriProdukSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->kategoriProdukSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function kategoriProdukSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->kategoriProdukSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    /////////////////////////////////////////////////////////////////////

    public function member_get()
	{
        $response = $this->LogModel->memberGet();
        return $this->returnData($response,false);
    }

    public function memberSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->memberSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function memberSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->memberSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function memberSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->memberSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    /////////////////////////////////////////////////////////////////////

    public function pegawai_get()
	{
        $response = $this->LogModel->pegawaiGet();
        return $this->returnData($response,false);
    }

    public function pegawaiSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->pegawaiSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function pegawaiSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->pegawaiSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function pegawaiSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->pegawaiSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    /////////////////////////////////////////////////////////////////////

    public function produk_get()
	{
        $response = $this->LogModel->produkGet();
        return $this->returnData($response,false);
    }

    public function produkSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->produkSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function produkSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->produkSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function produkSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->produkSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function rolePegawai_get()
	{
        $response = $this->LogModel->rolePegawaiGet();
        return $this->returnData($response,false);
    }

    public function rolePegawaiSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->rolePegawaiSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function rolePegawaiSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->rolePegawaiSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function rolePegawaiSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->rolePegawaiSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function supplier_get()
	{
        $response = $this->LogModel->supplierGet();
        return $this->returnData($response,false);
    }

    public function supplierSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->supplierSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function supplierSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->supplierSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function supplierSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->supplierSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function ukuranHewan_get()
	{
        $response = $this->LogModel->ukuranHewanGet();
        return $this->returnData($response,false);
    }

    public function ukuranHewanSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->ukuranHewanSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function ukuranHewanSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->ukuranHewanSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function ukuranHewanSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->ukuranHewanSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function pemesanan_get()
	{
        $response = $this->LogModel->pemesananGet();
        return $this->returnData($response,false);
    }

    public function pemesananSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->pemesananSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function pemesananSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->pemesananSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function pemesananSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->pemesananSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function transaksiLayanan_get()
	{
        $response = $this->LogModel->transaksiLayananGet();
        return $this->returnData($response,false);
    }

    public function transaksiLayananSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->transaksiLayananSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function transaksiLayananSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->transaksiLayananSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function transaksiLayananSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->transaksiLayananSearchByTime($time);
            return $this->returnData($response,false);
        }
    }
    
    ////////////////////////////////////////////////////////////////////

    public function transaksiPenjualan_get()
	{
        $response = $this->LogModel->transaksiPenjualanGet();
        return $this->returnData($response,false);
    }

    public function transaksiPenjualanSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->transaksiPenjualanSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function transaksiPenjualanSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->transaksiPenjualanSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function transaksiPenjualanSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->transaksiPenjualanSearchByTime($time);
            return $this->returnData($response,false);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function layanan_get()
	{
        $response = $this->LogModel->layananGet();
        return $this->returnData($response,false);
    }

    public function layananSearchByPegawai_post()
	{
        $pegawai =  $this->post('pegawai');
        if($pegawai!=null){
            $response = $this->LogModel->layananSearchByPegawai($pegawai);
            return $this->returnData($response,false);
        }
    }

    public function layananSearchByName_post()
	{
        $nama =  $this->post('nama');
        if($nama!=null){
            $response = $this->LogModel->layananSearchByName($nama);
            return $this->returnData($response,false);
        }
    }

    public function layananSearchByTime_post()
	{
        $time =  $this->post('time');
        $time = "%".$time."%";
        if($time!=null){
            $response = $this->LogModel->layananSearchByTime($time);
            return $this->returnData($response,false);
        }
    }
    
    public function returnData($msg, $error)
	{
		$response['error'] = $error;
		$response['message'] = $msg;
		return $this->response($response);
	}
}

