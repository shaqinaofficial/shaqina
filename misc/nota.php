<?php
require('../fpdf/fpdf.php');
include_once'../db/connect_db.php';

$id = $_GET['id'];
$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE inv_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$pdf = new FPDF('P','mm', array(80,200));

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(60,10,'Toko Hussein',0,1,'C');

$pdf->Line(10,18,72,18);
$pdf->Line(10,19,72,19);

$pdf->SetFont('Arial','',8);
$pdf->Cell(60,3,'Alamat: Jl. Jakarta Ruko No.1',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,3,'Menjual Bahan Bangunan, Perlengkapan Listri, DLL',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,4,'No. Telp: 0852-1495-0777 (Ali)',0,1,'C');

$pdf->Line(10,30,72,30);
$pdf->Line(10,31,72,31);

$pdf->SetY(31);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6 ,'Nota Pembelian',0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'No. Nota     :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->inv_id,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'Nama     :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->nama,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'Tanggal     :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(21,4 ,$row->order_date,0,0,'C');

//////////////////////////////////////////////
$pdf->SetY(55);

$pdf->SetX(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,8 ,'Produk',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(15,8 ,'Berat',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,8 ,'Total',1,1,'C');

$select = $pdo->prepare("SELECT * FROM tbl_invoice i INNER JOIN tbl_product p ON p.product_id = i.produk WHERE i.inv_id=$id");
$select->execute();
while($item = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(6);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35,5,$item->product_name,1,0,'L');
    $pdf->Cell(15,5,$item->product_berat . "g",1,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(18,5,'Rp '.number_format($item->harga),1,1,'R');
}

//////////////////////////////////////////////
$pdf->SetX(43);
$pdf->SetFont('Arial','Bi',8);
$pdf->Cell(25,8 ,'Total  :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,'Rp ' . number_format($row->total),0,1,'C');

$pdf->SetFont('Arial','Bi',8);
$pdf->Cell(30,8 ,'Total  :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,'Rp ' . number_format($row->total),0,1,'C');

//////////////////////////////////////////////
$pdf->SetY(120);
$pdf->SetX(7);
$pdf->SetFont('Arial','BU',5);
$pdf->Cell(75,4 ,'Pengembalian Tidak Diterima Tanpa Nota Pembelian',0,1,'L');

$pdf->SetFont('Arial','BU',5);
$pdf->Cell(45,4 ,'Pengembalian Diterima Maksimal 3 Hari Setelah Pembelian',0,0,'C');



$pdf->Output();

