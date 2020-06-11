<?php
include_once 'connectdb.php';

session_start();

if ($_SESSION['userEmail'] == "") {
  header('location:index.php');
}

include_once 'header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      商品資訊
      <small></small>
    </h1>
    
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><a href="productlist.php" class="btn btn-primary" role="button">回到商品列表</a></h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <div class="box-body">

        <?php
        $id = $_GET['id'];

        $select = $pdo->prepare("select * from tb_product where pid=$id");

        $select->execute();

        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
          echo '
                          <div class="col-md-6">
                            <ul class="list-group">
                                <center><p class="list-group-item list-group-item-success"><b>商品細節</b></p></center>
                                <li class="list-group-item"><b>ID</b> <span class="badge">' . $row->pid . '</span></li>
                                <li class="list-group-item"><b>商品名稱</b> <span class="label label-info pull-right">' . $row->pname . '</span></li>
                                <li class="list-group-item"><b>種類</b> <span class="label label-primary pull-right">' . $row->pcategory . '</span></li>
                              
                                <li class="list-group-item"><b>買入價格</b> <span class="label label-warning pull-right">' . $row->purchaseprice . '</span></li>
                                
                                <li class="list-group-item"><b>售出價格</b> <span class="label label-warning pull-right">' . $row->saleprice . '</span></li>
                                
                                <li class="list-group-item"><b>商品利潤 </b><span class="label label-success pull-right">' . ($row->saleprice - $row->purchaseprice) . '</span></li>
                                
                                <li class="list-group-item"><b>庫存 </b><span class="label label-danger pull-right">' . $row->pstock . '</span></li>
                                  
                                <li class="list-group-item"><b>描述:  </b><span class="">' . $row->pdescription . '</span></li>
                              
                            </ul>
                          </div>

                          <div class="col-md-6">
                              <ul class="list-group">
                                <center><p class="list-group-item list-group-item-success"><b>商品圖片</b></p></center>

                                <img src = "productimages/' . $row->pimage . '" class="img-responsive"/>
                                
                              </ul>
                          </div>
                          ';
        }
        ?>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include_once 'footer.php';

?>