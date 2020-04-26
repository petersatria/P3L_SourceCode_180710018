<?php
use Restserver\Libraries\REST_Controller;
require (APPPATH.'/libraries/REST_Controller.php');

Class NotaLayanan extends REST_Controller{
    
    function __construct() {
        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, OPTIO NS, POST, DELETE');
		header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
		parent::__construct();
        $this->load->model('NotaLayananModel');
        $this->load->library('pdf');
    }
    
    function index_get(){
        // $no = $this->post('no_transaksi');
        // $diskon = $this->post('diskon');
        $no = 'LY-010320-01';
        $diskon = 20000;
        $layanan = $this->get_layanan($no);

        $isMember = $layanan[0]->is_member;
        if ($isMember == 1) {
            $member = $this->get_member($layanan[0]->no_telp);
            $member_nama = $member[0]->nama;
        }
        else{
            $member_nama = '-';
            $member_telp = '-';
        }
        
        if($layanan[0]->id_CS != null) {
            $cs = $this->get_pegawai($layanan[0]->id_CS);
            $cs_nama = $cs[0]->nama;
        }
        else{
            $cs_nama = '-';
        }

        if($layanan[0]->id_cashier != null) {
            $kasir = $this->get_pegawai($layanan[0]->id_cashier);
            $kasir_nama = $kasir[0]->nama;
        }
        else{
            $kasir_nama = '-';
        }

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $image1 = "./resource/header.png";
        $pdf->SetFont('Arial','B',24);

        $pdf->Cell( 200, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 190), 0, 0, 'L', false );
        
        $pdf->Cell(10,53,'',0,1);

        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,6,'NOTA LUNAS',0,1,'C');

        $pdf->Cell(10,2,'',0,1);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190,6,$layanan[0]->created_at,0,1,'R');

        $pdf->Cell(10,4,'',0,1);

        $pdf->Cell(180,6,$no,0,1,'L');

        $pdf->Cell(10,4,'',0,1);

        $pdf->Cell(140,6,'Member : '.$member_nama,0,0,'L');
        $pdf->Cell(50,6,'CS    : '.$cs_nama,0,1,'L');
        $pdf->Cell(140,6,'Telepon : '.$layanan[0]->no_telp,0,0,'L');
        $pdf->Cell(50,6,'Kasir : '.$kasir_nama,0,1,'L');

        $pdf->Cell(190,6,'________________________________________________________________________________',0,1);
        $pdf->Cell(190,3,'',0,1,'C');
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,6,'Jenis Layanan',0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190,6,'________________________________________________________________________________',0,1);

        $pdf->Cell(10,6,'',0,1);
        
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(10,6,'No',1,0, 'C');
        $pdf->Cell(100,6,'Nama Jenis Layanan',1,0, 'C');
        $pdf->Cell(30,6,'Harga',1,0, 'C');
        $pdf->Cell(15,6,'Jumlah',1,0, 'C');
        $pdf->Cell(35,6,'Sub Total',1,1, 'C');
        $pdf->SetFont('Arial','',11, 'C');
        
        $total_harga = 0;

        foreach ($layanan as $key => $rows){
            $pdf->Cell(10,6,$key+1,1,0, 'C');
            $pdf->Cell(100,6,$rows->nama_layanan,1,0);
            $pdf->Cell(30,6,$this->rupiah($rows->harga),1,0);
            $pdf->Cell(15,6,$rows->jumlah,1,0,'C');
            $pdf->Cell(35,6,$this->rupiah($rows->harga_total),1,1);

            $total_harga = $total_harga + $rows->harga_total;
        }

        $total = $total_harga - $diskon;

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190,2,'',0,1,'R');
        $pdf->Cell(155,6,'Sub Total   ',0,0,'R');
        $pdf->Cell(35,6,$this->rupiah($total_harga),0,1,'L');

        $pdf->Cell(190,2,'',0,1,'R');
        $pdf->Cell(155,6,'Diskon   ',0,0,'R');
        $pdf->Cell(35,6,$this->rupiah($diskon),0,1,'L');

        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(190,2,'',0,1,'R');
        $pdf->Cell(155,6,'Total   ',0,0,'R');
        $pdf->Cell(35,6,$this->rupiah($total),0,1,'L');

        $pdf->Output();
    }

    function rupiah($angka){
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }

    public function get_layanan($no){
        return $this->NotaLayananModel->get($no);
    }

    public function get_pegawai($id_pegawai){
        return $this->NotaLayananModel->get_pegawai($id_pegawai);
    }

    public function get_member($id_member){
        return $this->NotaLayananModel->get_member($id_member);
    }
    
    public function test_get(){
        return $this->response($this->NotaLayananModel->get('LY-010320-01'));
    }
    
}