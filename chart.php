<?php
include_once 'connectdb.php';
session_start();
include('header.php');

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      DATE FILTER SALES REPORT
      <small></small>
    </h1>
    
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <form action="" method="post">

      <div class="box-body">
        <div class="box-header with-border">
          <div class="col-md-5">
            <!--
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker1">
     </div>
-->
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>

              <input type="text" class="form-control" id="datepicker1" name="date_1" value="" data-date-format="yyyy-mm-dd" required>
            </div>

          </div>
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>

              <input type="text" class="form-control" id="datepicker2" name="date_2" value="" data-date-format="yyyy-mm-dd" required>
            </div>
          </div>

          <div class="col-md-2">

            <div align="left">
              <input type="submit" name="datefilterBtn" class="btn btn-success" value="Filter By Date" />


            </div>

          </div>

          <br>
          <br>


        </div>
        <!--                                internal box header end-->

        <div class="chart">

        </div>
      </div>

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">bar Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">

          <?php


          $select = $pdo->prepare("select order_date,sum(total) as price from tb_invoice WHERE order_date BETWEEN :fromdate AND :todate group by order_date ");
          //            SELECT Date, sum(Total)  FROM Total where Date between '"+strOutput1+"' AND  '"+strOutput21+"' group by Date 

          //SELECT Date, sum(Total)  FROM Total where Date between '"+strOutput1+"' AND  '"+strOutput21+"' group by Date

          $select->bindParam(':fromdate', $_POST['date_1'], PDO::PARAM_STR);
          $select->bindParam(':todate', $_POST['date_2'], PDO::PARAM_STR);

          $select->execute();

          $jsontotal = [];
          $jsondate = [];
          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            $jsontotal[] = $price;
            $jsondate[] = $order_date;
          }

          //echo json_encode($json);
          //echo json_encode($json2);



          ?>






          <div class="chart">
            <!--                <canvas id="areaChart" style="height:250px"></canvas>-->
            <canvas id="myChart" style="height:250px"></canvas>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <?php
      $select = $pdo->prepare("select product_name,sum(qty) as qty from tb_invoice_details WHERE order_date BETWEEN :fromdate AND :todate group by product_id");
      //  SELECT Items, sum(Qty)  FROM Bill_items where Date between  '"+strOutput1+"' AND  '"+strOutput21+"' group by Dish_ID         
      $select->bindParam(':fromdate', $_POST['date_1'], PDO::PARAM_STR);
      $select->bindParam(':todate', $_POST['date_2'], PDO::PARAM_STR);

      $select->execute();

      $jsonproduct = [];
      $jsonqty = [];
      while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $jsonproduct[] = $product_name;
        $jsonqty[] = $qty;
      }
      ?>


      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Area Chart</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <!--                <canvas id="areaChart" style="height:250px"></canvas>-->
            <canvas id="productsale" style="height:250px"></canvas>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </form>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: <?php echo json_encode($jsondate); ?>,
      datasets: [{
        label: 'Total Sale $',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: <?php echo json_encode($jsontotal); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>

<script>
  var ctx = document.getElementById('productsale').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
      labels: <?php echo json_encode($jsonproduct); ?>,
      datasets: [{
        label: 'Total Quntity',
        backgroundColor: 'rgb(102, 255, 102)',
        borderColor: 'rgb(0, 102, 0)',
        data: <?php echo json_encode($jsonqty); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>


<script>
  $(document).ready(function() {
    //Date picker

    $('#datepicker1').datepicker({
      autoclose: true,

    }).val();


    $('#datepicker2').datepicker({
      autoclose: true,

    }).val();








  });
</script>







<?php include('footer.php'); ?>




































///////dashboard.php code////////////


<?php

include_once 'connectdb.php';
session_start();

if ($_SESSION['userEmail'] == "") {

  header('location:index.php');
}



$sql = "SELECT sum(total) AS total , sum(subtotal) as stotal,count(invoice_id) as inv FROM tb_invoice  ";
$stmt = $pdo->prepare($sql);

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);
$net_total = $row->total;

$total_invoice = $row->inv;







////////////////

$select = $pdo->prepare("select order_date,total from tb_invoice group by order_date LIMIT 30");
//            SELECT Date, sum(Total)  FROM Total where Date between '"+strOutput1+"' AND  '"+strOutput21+"' group by Date 

//SELECT Date, sum(Total)  FROM Total where Date between '"+strOutput1+"' AND  '"+strOutput21+"' group by Date


$select->execute();
$jsontotal = [];
$jsondate = [];
while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
  extract($row);
  $jsontotal[] = $total;
  $jsondate[] = $order_date;
}





//$select=$pdo->prepare("select product_name,sum(qty) as qty from tb_invoice_details group by product_id");
////  SELECT Items, sum(Qty)  FROM Bill_items where Date between  '"+strOutput1+"' AND  '"+strOutput21+"' group by Dish_ID         
//
//
//    $select->execute();
//     
//$jsonproduct=[];
//$jsonqty=[];
//while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
//    
//    extract($row);
//    
//    $jsonproduct[]= $product_name;
//    $jsonqty[]=$qty;
//}
//

///////////////










include_once 'header.php';

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Admin Dashboard
      <small></small>
    </h1>
    
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <!--------------------------
        | Your Page Content Here |
        -------------------------->
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h2><?php echo  number_format($total_invoice); ?></h2>

            <p>Total Orders</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>



      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo "$" . number_format($net_total, 2); ?><sup style="font-size: 10px"></sup></h3>

            <p>Total Revenue</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <?php

      $sql = "SELECT count(pname) as p FROM tb_product";
      $stmt = $pdo->prepare($sql);

      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_OBJ);


      $total_product = $row->p;
      ?>


      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo number_format($total_product); ?></h3>

            <p>Total Products </p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->

      <?php

      $sql = "SELECT count(category) as cate FROM tb_category";
      $stmt = $pdo->prepare($sql);

      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_OBJ);


      $cate = $row->cate;
      ?>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo $cate ?></h3>

            <p>Total Category</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>


    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Earning By Date</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <!--                <canvas id="areaChart" style="height:250px"></canvas>-->
            <canvas id="productpie" style="height:250px"></canvas>
          </div>
        </div>

        <!-- /.box-body -->
      </div>
    </div>
    <!-- /.box -->
    <div class="row">
      <div class="col-md-6">

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Best Selling Product</h3>


          </div>
          <div class="box-body">

            <table id="bestsellertable" class="table table-striped">

              <thead>
                <th>product ID</th>
                <th>商品名稱</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>

              </thead>






              <tbody>


                <?php

                //"SELECT Items as 'Dish',Rate as  'Price',sum(Qty) as 'Dish Qty' ,sum(Amount) as 'Amount' FROM Bill_items where Date between  '"+strOutput1+"' AND  '"+strOutput21+"' group by Dish_ID  ORDER BY sum(Qty) DESC

                $sql = "SELECT product_id,product_name ,price,sum(qty) as qty , sum(price*qty) as total FROM tb_invoice_details group by product_id order by sum(qty) DESC LIMIT 30";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':fromdate', $_POST['date_1'], PDO::PARAM_STR);
                $stmt->bindParam(':todate', $_POST['date_2'], PDO::PARAM_STR);
                $stmt->execute();
                // $total = $stmt->rowCount();
                // while ($row = $stmt->fetchObject()) {



                while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {




                  echo '
<tr>

<td>
    
       ' . $row->product_id . '
    </td>
    <td>
    
       ' . $row->product_name . '
    </td>


<td><span class="label label-info">' . $row->qty . '</span></td>
    <td><span class="label label-success">' . "$" . $row->price . '</span></td>
<td><span class="label label-danger">' . "$" . $row->total . '</span></td>
    
    
    </tr>';
                } ?>
              </tbody>

            </table>




          </div>
        </div>
      </div>

      <div class="col-md-6">

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Recent Orders</h3>


          </div>
          <div class="box-body">

            <table id="orderlisttable" class="table table-striped">

              <thead>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment Type</th>

              </thead>






              <tbody>

                <?php
                $select = $pdo->prepare("select * from tb_invoice  order by invoice_id desc LIMIT 50");

                $select->execute();

                while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                  echo '
    <tr>
    
    <td>
<a href="editorder.php?id=' . $row->invoice_id . '">' . $row->invoice_id . '</a>   
    
    </td>
    
    
   
    <td>' . $row->customer_name . '</td>
    <td>' . $row->order_date . '</td>
   <td><span class="label label-danger">' . "$" . $row->total . '</span></td>';



                  if ($row->payment_type == "Cash") {
                    echo '<td><span class="label label-primary">' . $row->payment_type . '</span></td>';
                  } elseif ($row->payment_type == "Card") {
                    echo '<td><span class="label label-warning">' . $row->payment_type . '</span></td>';
                  } else {
                    echo '<td><span class="label label-info">' . $row->payment_type . '</span></td>';
                  }
                }
                ?>

              </tbody>

            </table>




          </div>
        </div>




      </div>
    </div>





  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<script>
  var ctx = document.getElementById('productpie').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: <?php echo json_encode($jsondate); ?>,
      datasets: [{
        label: 'Total Earning',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: <?php echo json_encode($jsontotal); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });



  $(document).ready(function() {
    $('#bestsellertable').DataTable({





    });
  });


  $(document).ready(function() {
    $('#orderlisttable').DataTable({

      "order": [
        [0, "desc"]
      ]



    });
  });
</script>

<script>



</script>






<?php

include_once 'footer.php';

?>