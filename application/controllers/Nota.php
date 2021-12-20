<?php
use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

Class Nota extends REST_Controller{
    
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
    public function Nota_get(){
        $kode = $_GET['kode'];
        $totalPerawatan = 0;
        $totalProduk = 0;
        $transaksi = $this->TransaksiModel->search($kode);
        $date = strtotime($transaksi->tgl_transaksi);
        $tgl = date('Y-m-d',$date);
        $jam = date('H:i',$date);
        $customer = $this->CustomerModel->searchForeign($transaksi->kode_cust);
        $dokter = $this->PenanggungJawabModel->getByRole($transaksi->kode_transaksi,3);
        $kasir = $this->PenanggungJawabModel->getByRole($transaksi->kode_transaksi,6);
        $cs = $this->PenanggungJawabModel->getByRole($transaksi->kode_transaksi,5);
        $beautician = $this->PenanggungJawabModel->getByRole($transaksi->kode_transaksi,4);
        if ($transaksi->kode_promo == null) {
            $transaksi->kode_promo = '-';
        }


        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetMargins(20,5,20);

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell(180, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 1, 'C', false );
        
        $pdf->Cell(10,10,'',0,1);

        $pdf->SetFont('Arial','',15);
        $pdf->Cell(170,5,$tgl,0,1,'R');

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(140,5,$transaksi->kode_transaksi,0,0,'L');
        $pdf->SetFont('Arial','',15);
        $pdf->Cell(30,5,$jam,0,1,'R');

        $pdf->cell(30,5,'Cust',0,0,'L');
        $pdf->cell(140,5,$customer->nama_cust,0,1,'L');

        $pdf->cell(30,5,'DR',0,0,'L');
        $pdf->cell(90,5,$dokter,0,0,'L');
        $pdf->cell(30,5,'CS',0,0,'L');
        $pdf->cell(70,5,$cs,0,1,'L');
        $pdf->cell(30,5,'BC',0,0,'L');
        $pdf->cell(90,5,$beautician,0,0,'L');
        $pdf->cell(30,5,'PRO',0,0,'L');
        $pdf->cell(70,5,$transaksi->kode_promo,0,1,'L');

        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(170,0.8,'',0,1,'R',true);
        $pdf->Cell(170,6,'',0,1,'R');
        $pdf->Cell(170,0.3,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(170,8,'NOTA PERAWATAN NBC',0,1,'C');
        $pdf->Cell(170,0.3,'',0,1,'R',true);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(50,8,'Item',0,0,'L');
        $pdf->cell(30,8,'Satuan',0,0,'R');
        $pdf->cell(30,8,'Jumlah',0,0,'R');
        $pdf->cell(30,8,'Poin',0,0,'R');
        $pdf->cell(30,8,'Sub Total',0,1,'R');
        $pdf->Cell(170,0.3,'',0,1,'R',true);

        $detilPerawatan = $this->DetilTransaksiPerawatanModel->get($transaksi->kode_transaksi);
        foreach($detilPerawatan as $key => $p){
            $pdf->cell(50,8,$p->nama_prw,0,0,'L');
            $pdf->cell(30,8,$p->harga_prw,0,0,'R');
            $pdf->cell(30,8,$p->jumlah_prw,0,0,'R');
            if($p->isPoinUsed == 0){
                $pdf->cell(30,8,'0',0,0,'R');
            }else{
                $pdf->cell(30,8,$p->poin_prw * $p->jumlah_prw,0,0,'R');
            }
            
            $pdf->cell(30,8,$p->sub_total_prw,0,1,'R');
            $totalPerawatan = $totalPerawatan + (int)$p->sub_total_prw;

        }

        $pdf->Cell(170,0.8,'',0,1,'R',true);
        $pdf->Cell(170,8,$totalPerawatan,0,1,'R');
        $pdf->Cell(170,0.8,'',0,1,'R',true);

        $pdf->cell(100,15,'',0,1,'L');
        $pdf->Cell(170,0.3,'',0,1,'R',true);
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(170,8,'NOTA PRODUK NBC',0,1,'C');
        $pdf->Cell(170,0.3,'',0,1,'R',true);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(90,8,'Item',0,0,'L');
        $pdf->cell(20,8,'Satuan',0,0,'R');
        $pdf->cell(20,8,'Jumlah',0,0,'R');
        $pdf->cell(10,8,'',0,0,'R');
        $pdf->cell(30,8,'Sub Total',0,1,'R');
        $pdf->Cell(170,0.3,'',0,1,'R',true);

        $detilProduk = $this->DetilTransaksiProdukModel->get($transaksi->kode_transaksi);
        foreach($detilProduk as $key => $p){
            $pdf->cell(90,8,$p->nama_prd,0,0,'L');
            $pdf->cell(20,8,$p->harga_prd,0,0,'R');
            $pdf->cell(20,8,$p->jumlah_prd,0,0,'R');
            $pdf->cell(10,8,'',0,0,'R');
            $pdf->cell(30,8,$p->sub_total_prd,0,1,'R');
            

            $totalProduk = $totalProduk + $p->sub_total_prd;

        }
        $pdf->Cell(170,0.8,'',0,1,'R',true);
        $pdf->Cell(170,8,$totalProduk,0,1,'R');
        $pdf->Cell(170,0.8,'',0,1,'R',true);

        $pdf->cell(100,15,'',0,1,'L');
        $pdf->cell(60,5,'Cust',0,0,'L');
        $pdf->cell(60,5,'Kasir',0,0,'L');
        $pdf->cell(15,5,'Disc',0,0,'L');
        $pdf->cell(35,5,'Rp '.($totalProduk + $totalPerawatan - $transaksi->total) ,0,1,'R');

        $pdf->cell(60,10,'',0,0,'L');
        $pdf->cell(60,10,'',0,0,'L');
        $pdf->cell(15,10,'Total',0,0,'L');
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(35,10,'Rp '.$transaksi->total,0,1,'R');

        $pdf->SetFont('Arial','',12);
        $pdf->cell(60,10,'',0,0,'L');
        $pdf->cell(60,10,'',0,0,'L');
        $pdf->cell(15,10,'Tambah Poin',0,0,'L');
        $pdf->SetFont('Arial','B',15);
        $pdf->cell(35,10,round((int)$transaksi->total/50000-0.5)*5,0,1,'R');

        $pdf->SetFont('Arial','',12);
        $pdf->cell(100,5,'',0,1,'L');
        $pdf->cell(60,5,'('.$customer->nama_cust.')',0,0,'L');
        $pegawai = $this->PenanggungJawabModel->getByTransaksi($transaksi->kode_transaksi);
        foreach($pegawai as $key => $p){
            if($p->id_role_pegawai == 6){
                $pdf->cell(60,5,'('.$p->nama_pegawai.')',0,0,'L');
                break;
            }
        }

        $pdf->Output();
    }
}