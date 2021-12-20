<?php
use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

Class Laporan extends REST_Controller{
    
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
    public function customerByYear_get(){
        $tahun = $_GET['tahun'];

        $bulans = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $bulansInd = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
        $response = $this->CustomerModel->getRegisteredByYear($tahun);
        $tgl = date('d M Y');
        $total = 0;
        

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(20,5,20);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(180, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 1, 'C', false );
        
        $pdf->Cell(10,10,'',0,1);

        $pdf->SetFont('Arial','',15);

        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(170,0.8,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(170,12,'LAPORAN CUSTOMER BARU',0,1,'C');
        $pdf->SetFont('Arial','',15);
        $pdf->cell(30,5,'Tahun : ',0,0);
        $pdf->cell(30,5,$tahun,0,1);
        $pdf->cell(30,10,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->cell(20,10,'No',1,0,'C');
        $pdf->cell(40,10,'Bulan',1,0,'C');
        $pdf->cell(35,10,'Pria',1,0,'C');
        $pdf->cell(35,10,'Wanita',1,0,'C');
        $pdf->cell(40,10,'Jumlah',1,1,'C');

        $pdf->SetFont('Arial','',15);

        for ($i=0; $i < count($bulans); $i++) { 
            $temp = 0;
            $pdf->cell(20,10,$i+1,1,0,'C');
            $pdf->cell(40,10,' '.$bulansInd[$i],1,0,'L');
            foreach ($response as $key => $r) {
                if ($r->bulan == $bulans[$i] && $r->jenis_kelamin == 'L') {
                    $pdf->cell(35,10,$r->total.' ',1,0,'R');
                    $temp = $temp + $r->total;
                }
            }
            foreach ($response as $key => $r) {
                if ($r->bulan == $bulans[$i] && $r->jenis_kelamin == 'P') {
                    $pdf->cell(35,10,$r->total.' ',1,0,'R');
                    $temp = $temp + $r->total;
                }
            }
            $pdf->cell(40,10,$temp.' ',1,1,'R');
            $total = $total + $temp;
        }

        $pdf->SetFont('Arial','B',15);
        $pdf->cell(130,15,'Total',0,0,'R');
        $pdf->cell(40,15,$total,0,1,'R');
        $pdf->cell(170,35,'',0,1);

        $pdf->SetFont('Arial','',12);
        $pdf->cell(170,5,'dicetak tanggal '.date('d ').$bulansInd[date('m')-1].date(' Y'),0,1,'R');

        $pdf->Output();
    }

    public function pendapatanByYear_get(){
        $tahun = $_GET['tahun'];

        $bulansInd = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
        $response = $this->TransaksiModel->getPendapatanByYear($tahun);
        $tgl = date('d M Y');
        $total = 0;
        

        $pdf = new FPDF('L','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(30,5,30);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(237, 25, $pdf->Image($image1, 50, $pdf->GetY(), 200,30), 0, 1, 'C', false );
        
        $pdf->Cell(10,5,'',0,1);

        $pdf->SetFont('Arial','',15);

        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(237,0.8,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(237,12,'LAPORAN PENDAPATAN',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->cell(20,5,'Tahun : ',0,0);
        $pdf->cell(30,5,$tahun,0,1);
        $pdf->cell(30,5,'',0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->cell(20,8,'No',1,0,'C');
        $pdf->cell(47,8,'Bulan',1,0,'C');
        $pdf->cell(55,8,'Perawatan',1,0,'C');
        $pdf->cell(55,8,'Produk',1,0,'C');
        $pdf->cell(60,8,'Total',1,1,'C');

        $pdf->SetFont('Arial','',12);

        for ($i=0; $i < count($bulansInd); $i++) {

            $pdf->cell(20,8,$i+1,1,0,'C');
            $pdf->cell(47,8,' '.$bulansInd[$i],1,0,'C');
            $pdf->cell(55,8,$this->toRupiah($response[$i]->total_pendapatan_perawatan).' ',1,0,'R');
            $pdf->cell(55,8,$this->toRupiah($response[$i]->total_pendatapan_produk).' ',1,0,'R');
            $pdf->cell(60,8,$this->toRupiah($response[$i]->total_pendapatan_perawatan+$response[$i]->total_pendatapan_produk).' ',1,1,'R');

            $total = $total + $response[$i]->total_pendapatan_perawatan+$response[$i]->total_pendatapan_produk;
        }

        $pdf->SetFont('Arial','B',12);
        $pdf->cell(177,8,'Total',0,0,'R');
        $pdf->cell(60,8,' '.$this->toRupiah($total),1,1,'R');
        $pdf->cell(170,10,'',0,1);

        $pdf->SetFont('Arial','',10);
        $pdf->cell(237,5,'dicetak tanggal '.date('d ').$bulansInd[date('m')-1].date(' Y'),0,1,'R');

        $pdf->Output();
    }

    public function produkLaris_get(){
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $date=$bulan.'-'.$tahun;

        $bulansInd = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
        $response = $this->DetilTransaksiProdukModel->getTotalLaporan($date);
        $tgl = date('d M Y');
        $total = 0;
        

        $pdf = new FPDF('L','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(30,5,30);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(237, 25, $pdf->Image($image1, 50, $pdf->GetY(), 200,30), 0, 1, 'C', false );
        
        $pdf->Cell(10,5,'',0,1);

        $pdf->SetFont('Arial','',15);

        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(237,0.8,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(237,12,'LAPORAN 10 PRODUK PALING LARIS',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->cell(20,5,'Tahun : ',0,0);
        $pdf->cell(30,5,$tahun,0,1);
        $pdf->cell(20,8,'Bulan : ',0,0);
        $pdf->cell(30,8,$bulansInd[$bulan-1],0,1);
        $pdf->cell(30,5,'',0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->cell(20,8,'No',1,0,'C');
        $pdf->cell(100,8,'Nama Produk',1,0,'C');
        $pdf->cell(47,8,'Harga',1,0,'C');
        $pdf->cell(30,8,'Ukuran',1,0,'C');
        $pdf->cell(40,8,'Jumlah Pembelian',1,1,'C');

        $pdf->SetFont('Arial','',11);

        for ($i=0; $i < count($response); $i++) {

            $pdf->cell(20,8,$i+1,1,0,'C');
            $pdf->cell(100,8,' '.$response[$i]->nama_prd,1,0,'L');
            $pdf->cell(47,8,' '.$this->toRupiah($response[$i]->harga_prd),1,0,'R');
            $pdf->cell(30,8,' '.$response[$i]->ukuran_prd,1,0,'R');
            $pdf->cell(40,8,' '.$response[$i]->total,1,1,'R');
        }

        $pdf->cell(170,10,'',0,1);

        $pdf->SetFont('Arial','',10);
        $pdf->cell(237,5,'dicetak tanggal '.date('d ').$bulansInd[date('m')-1].date(' Y'),0,1,'R');

        $pdf->Output();
    }

    public function perawatanLaris_get(){
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $date=$bulan.'-'.$tahun;

        $bulansInd = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
        $response = $this->DetilTransaksiPerawatanModel->getTotalLaporan($date);
        $tgl = date('d M Y');
        $total = 0;
        

        $pdf = new FPDF('L','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(30,5,30);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(237, 25, $pdf->Image($image1, 50, $pdf->GetY(), 200,30), 0, 1, 'C', false );
        
        $pdf->Cell(10,5,'',0,1);

        $pdf->SetFont('Arial','',15);

        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(237,0.8,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(237,12,'LAPORAN 10 PERAWATAN PALING LARIS',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->cell(20,5,'Tahun : ',0,0);
        $pdf->cell(30,5,$tahun,0,1);
        $pdf->cell(20,8,'Bulan : ',0,0);
        $pdf->cell(30,8,$bulansInd[$bulan-1],0,1);
        $pdf->cell(30,5,'',0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->cell(20,8,'No',1,0,'C');
        $pdf->cell(130,8,'Nama Perawatan',1,0,'C');
        $pdf->cell(47,8,'Harga',1,0,'C');
        $pdf->cell(40,8,'Jumlah Pembelian',1,1,'C');

        $pdf->SetFont('Arial','',11);

        for ($i=0; $i < count($response); $i++) {

            $pdf->cell(20,8,$i+1,1,0,'C');
            $pdf->cell(130,8,' '.$response[$i]->nama_prw,1,0,'L');
            $pdf->cell(47,8,' '.$this->toRupiah($response[$i]->harga_prw),1,0,'R');
            $pdf->cell(40,8,' '.$response[$i]->total,1,1,'R');
        }

        $pdf->cell(170,10,'',0,1);

        $pdf->SetFont('Arial','',10);
        $pdf->cell(237,5,'dicetak tanggal '.date('d ').$bulansInd[date('m')-1].date(' Y'),0,1,'R');

        $pdf->Output();
    }

    public function toRupiah($value){
        $value = strrev($value);
        $value = str_split($value, 3);
        $temp = '';
        for ($i=count($value); $i>0 ; $i--) {
            if($i==1){
                $temp = $temp.strrev($value[$i-1]);
            }else{
                $temp = $temp.strrev($value[$i-1]).','; 
            }
        }
        return $temp;
    }


}