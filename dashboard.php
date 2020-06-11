<?php
      include_once 'connectdb.php';
      session_start();
      if ($_SESSION['userEmail'] == "") header('location:index.php');
      include_once 'header.php';
      $select = $pdo->prepare("select sum(total) as t , count(invoice_id) as inv from tb_invoice");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);

      $total_order = $row->inv;

      $net_total = $row->t;

      $select = $pdo->prepare("select order_date, total from tb_invoice  group by order_date LIMIT 30");
      $select->execute();

      $ttl = [];
      $date = [];

      while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $ttl[] = $total;
        $date[] = $order_date;
      }
// echo json_encode($total);  
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      管理者看板
      <small></small>
    </h1>
    
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <div class="box-body">

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_order; ?></h3>

              <p>所有訂單</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">更多... <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo "$" . number_format($net_total, 2); ?><sup style="font-size: 20px"></sup></h3>

              <p>總營收</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">更多... <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <?php
        $select = $pdo->prepare("select count(pname) as p from tb_product");
        $select->execute();
        $row = $select->fetch(PDO::FETCH_OBJ);

        $total_product = $row->p;
        ?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_product; ?></h3>

              <p>所有商品</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">更多... <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <?php
        $select = $pdo->prepare("select count(category) as cate from tb_category");
        $select->execute();
        $row = $select->fetch(PDO::FETCH_OBJ);

        $total_category = $row->cate;
        ?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $total_category; ?></h3>

              <p>所有種類</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">更多... <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>


      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">營收概狀</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
          <div class="chart">
            <canvas id="earningbydate" style="height:250px"></canvas>


          </div>



        </div>
      </div>


      <div class="row">
        <div class="col-md-6">

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">熱銷品</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">

              <table id="bestsellingproductlist" class="table table-striped">
                <thead>
                  <tr>
                    <th>商品 ID</th>
                    <th>商品名稱</th>
                    <th>數量</th>
                    <th>價格</th>
                    <th>總計</th>
                  </tr>

                </thead>

                <tbody>

                  <?php
                  $select = $pdo->prepare("select product_id,product_name,price,sum(qty) as q , sum(qty*price) as total from tb_invoice_details group by product_id order by sum(qty) DESC LIMIT 15");

                  $select->execute();

                  while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                    echo '
                          <tr>
                          <td>' . $row->product_id . '</td>
                          <td>' . $row->product_name . '</td>
                          <td><span class="label label-info">' . $row->q . '</span></td>
                          <td><span class="label label-success">' . "$" . $row->price . '</span></td>
                          <td><span class="label label-danger">' . "$" . $row->total . '</span></td>
                             </tr>
                             ';
                  }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">近期訂單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">

              <table id="orderlisttable" class="table table-striped">
                  <thead>
                    <tr>
                      <th> ID</th>
                      <th>客戶名稱</th>
                      <th>訂單日期</th>
                      <th>總額</th>
                      <th>付款方式</th>

                    </tr>

                  </thead>
              <tbody>

                  <?php
                        $select = $pdo->prepare("select * from tb_invoice  order by invoice_id desc LIMIT 15");
                        $select->execute();

                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                          echo '
                                <tr>
                                <td><a href="editorder.php?id=' . $row->invoice_id . '">' . $row->invoice_id . '</a></td>
                                <td>' . $row->customer_name . '</td>
                                <td>' . $row->order_date . '</td>
                                <td><span class="label label-danger">' . "$" . $row->total . '</span></td>';


                              if ($row->payment_type == "Cash") {
                                echo '<td><span class="label label-warning">' . $row->payment_type . '</span></td>';
                              } elseif ($row->payment_type == "Card") {
                                echo '<td><span class="label label-success">' . $row->payment_type . '</span></td>';
                              } else {
                                echo '<td><span class="label label-primary">' . $row->payment_type . '</span></td>';
                              }
                        }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  const ctx = document.getElementById('earningbydate').getContext('2d');
  const chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',
    // The data for our dataset
    data: {
      labels: <?php echo json_encode($date); ?>,
      datasets: [{
        label: '總營收',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(250,128,114)',
        data: <?php echo json_encode($ttl); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>

<?php include_once 'footer.php'; ?>