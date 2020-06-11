<?php
include_once 'connectdb.php';
session_start();
// 依照session保留的紀錄決定是否進入user dashboard介面
if($_SESSION['userEmail']=="" OR $_SESSION['role']=="Admin"){
    header('location:index.php'); // 做redirect
}


include_once 'headeruser.php';

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       User Dashboard
        <small></small>
      </h1>
     
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include_once 'footer.php';?>