<?php
use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

Class KartuCustomer extends REST_Controller{
    
    function __construct() {
        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
        $this->load->library('pdf');
        $this->load->model('TransaksiModel');
        $this->load->model('CustomerModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->model('DetilTransaksiPerawatanModel');
        $this->load->model('DetilTransaksiProdukModel');
        $this->load->model('PerawatanModel');
        $this->load->model('ProdukModel');
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/LayananTerlaris?tahun=2020
    public function index_get(){
        $kode = $_GET['kode'];
        $customer = $this->CustomerModel->search($kode);
        $date = strtotime($customer->tgl_registrasi_cust);
        $tgl = date('m/y',$date);
        $customer->kode_cust = str_split($customer->kode_cust, 4);

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(20,5,20);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(150, 20, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 150), 0, 1, 'C', false );
        
        $pdf->Cell(10,10,'',0,1);

        $pdf->SetFont('Arial','',20);
        $pdf->Cell(120,10,$customer->kode_cust[0].' '.$customer->kode_cust[1].' '.$customer->kode_cust[2].' '.$customer->kode_cust[3],0,1,'C');
        
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(10,10,'',0,0,'C');
        $pdf->Cell(50,10,'Card Holder Since',0,1,'L');
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(10,10,'',0,0,'C');
        $pdf->Cell(50,1,'Month/Year',0,1,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10,10,'',0,0,'C');
        $pdf->Cell(50,10,$tgl,0,1,'L');

        $pdf->SetFont('Arial','',20);
        $pdf->Cell(10,10,'',0,0,'C');
        $pdf->Cell(120,10,$customer->nama_cust,0,1,'L');

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(120,10,'Your NBC Card has no expiration date',0,1,'R');


        $pdf->Output();
    }
}