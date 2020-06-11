<?php

include_once 'connectdb.php';

$id = $_POST['pidd'];

// DELETE T1, T2 FROM T1 INNER JOIN T2 ON T1.key = T2.key  WHERE condition T1.key=id;

$sql = "delete tb_invoice , tb_invoice_details FROM tb_invoice INNER JOIN tb_invoice_details ON tb_invoice.invoice_id = tb_invoice_details.invoice_id where tb_invoice.invoice_id=$id";

//$sql="delete from tb_product where pid=$id";
$delete = $pdo->prepare($sql);
if ($delete->execute()) {
  
} else {
  echo 'Error in Deleting';
}
