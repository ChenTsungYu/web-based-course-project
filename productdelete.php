<?php

include_once 'connectdb.php';

if ($_SESSION['userEmail'] == "") {
  header('location:index.php');
}
$id = $_POST['pidd'];
$sql = "delete from tb_product where pid=$id";

$delete = $pdo->prepare($sql);

if ($delete->execute()) {
} else {

  echo 'Error in Deleting';
}
