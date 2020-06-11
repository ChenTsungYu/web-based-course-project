<?php
//call the FPDF library
require('fpdf/fpdf.php');
include_once 'connectdb.php';


$id = $_GET['id'];
$select = $pdo->prepare("select * from tb_invoice where invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

//create pdf object
$pdf = new FPDF('P', 'mm', array(80, 200));

// 說明： $pdf = new FPDF('P', 'mm', 'A4);
//  orientation (P or L) - portriat landscape
//  unit (pt , mm , am , and in)  - unit
// Mixed format (A3 A4 A5, Letter and legal)

//add new page
$pdf->AddPage();

//set font to arial, bold, 16pt
$pdf->SetFont('Arial', 'B', 16);
//Cell(width , height , text , border , end line , [align] )
$pdf->Cell(60, 8, 'CYBARG Inc', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 8);

$pdf->Cell(60, 5, 'Address : Progress way , New York - USA', 0, 1, 'C');
$pdf->Cell(60, 5, 'Phone Number: 347-4567-2314', 0, 1, 'C');
$pdf->Cell(60, 5, 'E-mail Address : faizan@cybarg.com', 0, 1, 'C');
$pdf->Cell(60, 5, 'Website : www.cybarg.com', 0, 1, 'C');


//Line(x1,y1,x2,y2);

$pdf->Line(7, 38, 72, 38);


$pdf->Ln(1); // line 


$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Bill To :', 0, 0, '');


$pdf->SetFont('Courier', 'BI', 8);
$pdf->Cell(40, 4, $row->customer_name, 0, 1, '');


$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Invoice no :', 0, 0, '');


$pdf->SetFont('Courier', 'BI', 8);
$pdf->Cell(40, 4, $row->invoice_id, 0, 1, '');



$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Date :', 0, 0, '');


$pdf->SetFont('Courier', 'BI', 8);
$pdf->Cell(40, 4, $row->order_date, 0, 1, '');



/////////


$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);

$pdf->Cell(34, 5, 'PRODUCT', 1, 0, 'C');   //70
$pdf->Cell(11, 5, 'QTY', 1, 0, 'C');
$pdf->Cell(8, 5, 'PRC', 1, 0, 'C');
$pdf->Cell(12, 5, 'TOTAL', 1, 1, 'C');


$select = $pdo->prepare("select * from tb_invoice_details where invoice_id=$id");
$select->execute();

while ($item = $select->fetch(PDO::FETCH_OBJ)) {
  $pdf->SetX(7);
  $pdf->SetFont('Helvetica', 'B', 8);
  $pdf->Cell(34, 5, $item->product_name, 1, 0, 'L');
  $pdf->Cell(11, 5, $item->qty, 1, 0, 'C');
  $pdf->Cell(8, 5, $item->price, 1, 0, 'C');
  $pdf->Cell(12, 5, $item->price * $item->qty, 1, 1, 'C');
}




/////////




$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'SUBTOTAL', 1, 0, 'C');
$pdf->Cell(20, 5, $row->subtotal, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'TAX(5%)', 1, 0, 'C');
$pdf->Cell(20, 5, $row->tax, 1, 1, 'C');

$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'DISCOUNT', 1, 0, 'C');
$pdf->Cell(20, 5, $row->discount, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 10);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'GRAND TOTAL', 1, 0, 'C');
$pdf->Cell(20, 5, $row->total, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'PAID', 1, 0, 'C');
$pdf->Cell(20, 5, $row->paid, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'DUE', 1, 0, 'C');
$pdf->Cell(20, 5, $row->due, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');   //190
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'PAYMENT TYPE', 1, 0, 'C');
$pdf->Cell(20, 5, $row->payment_type, 1, 1, 'C');



$pdf->Cell(20, 5, '', 0, 1, '');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(25, 5, 'Important Notice :', 0, 1, '');

$pdf->SetX(7);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(75, 5, 'No item will be replaced or refunded if you dont have the invoice with you. ', 0, 2, '');

$pdf->SetX(7);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(75, 5, 'You can refund within 2 days of purchase. ', 0, 1, '');




$pdf->Output();
