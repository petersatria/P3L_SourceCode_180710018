<?php
use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

Class Laporan extends REST_Controller{
    
    function __construct() {
        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
        $this->load->model('LaporanModel');
        $this->load->library('pdf');
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/LayananTerlaris?tahun=2020
    public function LayananTerlaris_get(){
        $tahun = $_GET['tahun'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Jasa Layanan Terlaris',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,6,'Tahun :',0,0);
        $pdf->Cell(20,6,$tahun,0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,'No',1,0, 'C');
        $pdf->Cell(30,10,'Bulan',1,0, 'C');
        $pdf->Cell(110,10,'Nama Layanan',1,0, 'C');
        $pdf->Cell(40,10,'Jumlah Penjualan',1,1, 'C');

        $pdf->SetFont('Arial','',11, 'C');

        for ($i = 1; $i <=12; $i++){
            $data = $this->LaporanModel->terlaris($tahun,$i,'L');

            $pdf->Cell(10,10,"1",1,0,'C');
            $pdf->Cell(30,10," ".$this->get_bulan($i),1,0);

            if ($data == null) {
                $pdf->Cell(110,10," -",1,0);
                $pdf->Cell(40,10," -",1,1,'C');
            }
            else {
                $pdf->Cell(110,10," ".$data[0]->nama,1,0);
                $pdf->Cell(40,10," ".$data[0]->jumlah,1,1,'C');
            }
        }

        $pdf->Cell(110,15,"",0,1);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/ProdukTerlaris?tahun=2020
    public function ProdukTerlaris_get(){
        $tahun = $_GET['tahun'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Produk Terlaris',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,6,'Tahun :',0,0);
        $pdf->Cell(20,6,$tahun,0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,'No',1,0, 'C');
        $pdf->Cell(30,10,'Bulan',1,0, 'C');
        $pdf->Cell(110,10,'Nama Produk',1,0, 'C');
        $pdf->Cell(40,10,'Jumlah Penjualan',1,1, 'C');

        $pdf->SetFont('Arial','',11, 'C');

        for ($i = 1; $i <=12; $i++){
            $data = $this->LaporanModel->terlaris($tahun,$i,'P');

            $pdf->Cell(10,10,"1",1,0,'C');
            $pdf->Cell(30,10," ".$this->get_bulan($i),1,0);

            if ($data == null) {
                $pdf->Cell(110,10," -",1,0);
                $pdf->Cell(40,10," -",1,1,'C');
            }
            else {
                $pdf->Cell(110,10," ".$data[0]->nama,1,0);
                $pdf->Cell(40,10," ".$data[0]->jumlah,1,1,'C');
            }
        }

        $pdf->Cell(110,15,"",0,1);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/PendapatanTahunan?tahun=2020
    public function PendapatanTahunan_get(){
        $tahun = $_GET['tahun'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Pendapatan Tahunan',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,6,'Tahun :',0,0);
        $pdf->Cell(20,6,$tahun,0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(30,10,' Bulan',1,0, 'L');
        $pdf->Cell(50,10,' Jasa Layanan',1,0, 'L');
        $pdf->Cell(50,10,' Produk',1,0, 'L');
        $pdf->Cell(50,10,' Total',1,1, 'L');

        $pdf->SetFont('Arial','',11, 'C');

        $total = 0;

        for ($i = 1; $i <=12; $i++){
            $data = $this->LaporanModel->pendapatanTahunan($tahun,$i);

            $pdf->Cell(10,10,"1",1,0,'C');
            $pdf->Cell(30,10," ".$this->get_bulan($i),1,0);

            if ($data[0]->layanan == 0) {
                $pdf->Cell(50,10," Rp 0,00",1,0);
            }
            else {
                $pdf->Cell(50,10," ".$this->rupiah($data[0]->layanan),1,0);
            }

            if ($data[0]->produk == 0) {
                $pdf->Cell(50,10," Rp 0,00",1,0);
            }
            else {
                $pdf->Cell(50,10," ".$this->rupiah($data[0]->produk),1,0);
            }
            
            $pdf->Cell(50,10," ".$this->rupiah($data[0]->produk + $data[0]->layanan),1,1);
            
            $total =  $total + $data[0]->produk + $data[0]->layanan;
        }

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,15,"Total  ".$this->rupiah($total),0,1,'R');
        $pdf->Cell(110,10,"",0,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/PendapatanBulanan?tahun=2020&bulan=3
    public function PendapatanBulanan_get(){
        $tahun = $_GET['tahun'];
        $bulan = $_GET['bulan'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Pendapatan Bulanan',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,6,'Bulan :',0,0);
        $pdf->Cell(20,6,$this->get_bulan($bulan),0,1);
        $pdf->Cell(14,6,'Tahun :',0,0);
        $pdf->Cell(20,6,$tahun,0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(90,10,' Nama Jasa Layanan',1,0, 'L');
        $pdf->Cell(90,10,' Harga',1,1, 'L');

        $pdf->SetFont('Arial','',11, 'C');

        $total = 0;
        $layanan = $this->LaporanModel->pendapatanBulanan($tahun,$bulan,'L');
        foreach ($layanan as $key => $rows){
            $pdf->Cell(10,10,$key+1,1,0, 'C');
            $pdf->Cell(90,10," ".$rows->layanan,1,0);
            $pdf->Cell(90,10," ".$this->rupiah($rows->harga),1,1);

            $total = $total + $rows->harga;
        }

        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,15,"Total  ".$this->rupiah($total),0,1,'R');
        
        $pdf->Cell(110,10,"",0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(90,10,' Nama Produk',1,0, 'L');
        $pdf->Cell(90,10,' Harga',1,1, 'L');

        $pdf->SetFont('Arial','',11, 'C');

        $total = 0;
        $produk = $this->LaporanModel->pendapatanBulanan($tahun,$bulan,'P');
        foreach ($produk as $key => $rows){
            $pdf->Cell(10,10,$key+1,1,0, 'C');
            $pdf->Cell(90,10," ".$rows->produk,1,0);
            $pdf->Cell(90,10," ".$this->rupiah($rows->harga),1,1);

            $total = $total + $rows->harga;
        }
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,15,"Total  ".$this->rupiah($total),0,1,'R');

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(10,6,'',0,1);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/PengadaanProdukTahunan?tahun=2020
    public function PengadaanProdukTahunan_get(){
        $tahun = $_GET['tahun'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Pengadaan Produk Tahunan',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,6,'Tahun :',0,0);
        $pdf->Cell(20,6,$tahun,0,1);

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(50,10,' Bulan',1,0, 'L');
        $pdf->Cell(130,10,' Jumlah Pengeluaran',1,1, 'L');

        $pdf->SetFont('Arial','',11, 'C');

        $total = 0;

        for ($i = 1; $i <=12; $i++){
            $data = $this->LaporanModel->pengadaanProdukTahunan($tahun,$i);

            $pdf->Cell(10,10,"1",1,0,'C');
            $pdf->Cell(50,10," ".$this->get_bulan($i),1,0);

            if ($data[0]->total == 0) {
                $pdf->Cell(130,10," Rp 0,00",1,1);
            }
            else {
                $pdf->Cell(130,10," ".$this->rupiah($data[0]->total),1,1);
            }
            
            $total =  $total + $data[0]->total;
        }

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,15,"Total  ".$this->rupiah($total),0,1,'R');
        $pdf->Cell(110,10,"",0,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/PengadaanProdukBulanan?tahun=2020&bulan=3
    public function PengadaanProdukBulanan_get(){
        $tahun = $_GET['tahun'];
        $bulan = $_GET['bulan'];

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Laporan Pengadaan Produk Tahunan',0,1,'C');

        $pdf->Cell(10,6,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');
        $pdf->Cell(14,10,'Bulan :',0,0);
        $pdf->Cell(20,10,$this->get_bulan($bulan),0,1);
        $pdf->Cell(14,10,'Tahun :',0,0);
        $pdf->Cell(20,10,$tahun,0,1);

        $pdf->Cell(10,3,'',0,1);

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(90,10,' Nama Produk',1,0, 'L');
        $pdf->Cell(90,10,' Jumlah Pengeluaran',1,1, 'L');

        $pdf->SetFont('Arial','',11, 'C');

        $total = 0;
        $produk = $this->LaporanModel->pengadaanProdukBulanan($tahun,$bulan);
        foreach ($produk as $key => $rows){
            $pdf->Cell(10,10,"1",1,0,'C');
            $pdf->Cell(90,10," ".$rows->produk,1,0);

            if ($rows->total == 0) {
                $pdf->Cell(90,10," Rp 0,00",1,1);
            }
            else {
                $pdf->Cell(90,10," ".$this->rupiah($rows->total),1,1);
            }
            
            $total =  $total + $rows->total;
        }

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,15,"Total  ".$this->rupiah($total),0,1,'R');
        $pdf->Cell(110,10,"",0,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    //http://localhost/rest_api-kouvee-pet-shop/index.php/Laporan/SuratPemesanan?no=PO-2020-04-24-06
    public function SuratPemesanan_get(){
        $no = $_GET['no'];
        $pemesanan = $produk = $this->LaporanModel->pemesanan($no);

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(190,6,'Surat Pemesanan',0,1,'C');

        $pdf->Cell(10,6,'',0,1);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(190,10,'No : '.$no, 0, 1, 'R');

        

        $tahun = substr($pemesanan[0]->tgl_pemesanan,0,4);
        $bulan_convert = (int)substr($pemesanan[0]->tgl_pemesanan,5,2);
        $bulan = $this->get_bulan($bulan_convert);
        $hari = substr($pemesanan[0]->tgl_pemesanan,8,9);
        $pdf->Cell(190,10,'Tanggal : '.$hari.' '.$bulan.' '.$tahun,0,1,'R');

        

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,6,' Kepada Yth: ',0,1, 'L');
        $pdf->Cell(190,6,' '.$pemesanan[0]->nama,0,1, 'L');
        $pdf->Cell(190,6,' '.$pemesanan[0]->kota,0,1, 'L');
        $pdf->Cell(190,6,' '.$pemesanan[0]->no_telp,0,1, 'L');

        $pdf->Cell(10,5,'',0,1);

        $pdf->SetFont('Arial','',11, 'C');

        $pdf->Cell(190,10,'Mohon untuk disediakan produk-produk berikut ini :', 0, 1, 'L');
        
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,10,' No',1,0, 'L');
        $pdf->Cell(80,10,' Nama Produk',1,0, 'L');
        $pdf->Cell(50,10,' Satuan',1,0, 'L');
        $pdf->Cell(50,10,' Jumlah',1,1, 'L');

        $detil = $this->LaporanModel->detail_pemesanan($no);
        $i = 1;
        $pdf->SetFont('Arial','',11);
        foreach ($detil as $key => $rows){
            
            $pdf->Cell(10,10,$i,1,0,'C');
            $pdf->Cell(80,10," ".$rows->nama,1,0);
            $pdf->Cell(50,10," ".$rows->satuan,1,0);
            $pdf->Cell(50,10," ".$rows->jumlah,1,1);
            
            $i++;
        }
        $pdf->Cell(110,20,"",0,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,10,"Dicetak tanggal ".date('d')." ".$this->get_bulan(date('n'))." 2020",0,1,'R');

        $pdf->Output();
    }

    public function index_post() {
        $tahun = 2020;
        $bulan = 3;
        return $this->response($this->LaporanModel->pendapatanTahunan(2020,4,'P'));
    }

    function rupiah($angka){
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }

    public function get_bulan($bulan) {
        if ($bulan == 1) {
            return "Januari";
        }

        else if ($bulan == 2) {
            return "Febuari";
        }

        else if ($bulan == 3) {
            return "Maret";
        }

        else if ($bulan == 4) {
            return "April";
        }

        else if ($bulan == 5) {
            return "Mei";
        }

        else if ($bulan == 6) {
            return "Juni";
        }

        else if ($bulan == 7) {
            return "Juli";
        }

        else if ($bulan == 8) {
            return "Agustus";
        }

        else if ($bulan == 9) {
            return "September";
        }

        else if ($bulan == 10) {
            return "Oktober";
        }

        else if ($bulan == 11) {
            return "November";
        }

        else if ($bulan == 12) {
            return "Desember";
        }
        
        else {
            return "ERROR";
        }   
    }
}