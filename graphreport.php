<?php
include_once 'connectdb.php';
error_reporting(0);
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
      視覺化報表
      <small></small>
    </h1>
  
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-warning">
      <form action="" method="post" name="">

        <div class="box-header with-border">
          <h3 class="box-title">From : <?php echo $_POST['date_1'] ?> -- To : <?php echo $_POST['date_2'] ?></h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-5">

              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1" data-date-format="yyyy-mm-dd">
              </div>

            </div>

            <div class="col-md-5">

              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker2" name="date_2" data-date-format="yyyy-mm-dd">
              </div>

            </div>

            <div class="col-md-2">
              <div align="left">

                <input type="submit" name="btndatefilter" value="日期篩選" class="btn btn-success">

              </div>
            </div>
          </div>

          <br>
          <br>
          <?php
          $select = $pdo->prepare("select order_date, sum(total) as price from tb_invoice  where order_date between :fromdate AND :todate group by order_date");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);

          $select->execute();

          $total = [];
          $date = [];

          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            $total[] = $price;
            $date[] = $order_date;
          }
          // echo json_encode($total);  

          ?>

          <div class="chart">
            <canvas id="myChart" style="height:250px"></canvas>
          </div>
          <?php
          $select = $pdo->prepare("select product_name, sum(qty) as q from tb_invoice_details  where order_date between :fromdate AND :todate group by product_id");
          $select->bindParam(':fromdate', $_POST['date_1']);
          $select->bindParam(':todate', $_POST['date_2']);

          $select->execute();

          $pname = [];
          $qty = [];

          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            $pname[] = $product_name;
            $qty[] = $q;
          }
          // echo json_encode($total);  

          ?>

          <div class="chart">
            <canvas id="bestsellingproduct" style="height:250px"></canvas>


          </div>




        </div>
      </form>
    </div>




  </section>
  <!--/.content -->
</div>
<!-- /.content-wrapper -->


<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',
    // The data for our dataset
    data: {
      labels: <?php echo json_encode($date); ?>,
      datasets: [{
        label: '總營收',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(250,128,114)',
        data: <?php echo json_encode($total); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>


<script>
  var ctx = document.getElementById('bestsellingproduct').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: <?php echo json_encode($pname); ?>,
      datasets: [{
        label: '總數量',
        backgroundColor: 'rgb(0,191,255)',
        borderColor: 'rgb(143,188,143)',
        data: <?php echo json_encode($qty); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>

<script>
  //Date picker
  $('#datepicker1').datepicker({
    autoclose: true
  });

  //Date picker
  $('#datepicker2').datepicker({
    autoclose: true
  });
</script>
<?php

include_once 'footer.php';

?>