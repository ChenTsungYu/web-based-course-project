<?php
include_once 'connectdb.php';
session_start();
if($_SESSION['userEmail']==""){
  header('location:index.php');
}
include_once 'header.php';  
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

    <div class="box box-warning">
      <!--            <form  action="" method="post" name="">-->

      <div class="box-header with-border">
        <h3 class="box-title">訂單列表</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <div class="box-body">

        <div style="overflow-x:auto;">

          <table id="orderListTable" class="table table-striped">
            <thead>
              <tr>
                <th>發票 ID</th>
                <th>客戶名</th>
                <th>訂單日期</th>
                <th>總計</th>
                <th>已付</th>
                <th>欠款</th>
                <th>付款方式</th>
                <th>列印</th>

                <th>編輯</th>
                <th>刪除</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $select = $pdo->prepare("select * from tb_invoice  order by invoice_id desc");

              $select->execute();

              while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                echo '
                <tr>
                <td>' . $row->invoice_id . '</td>
                <td>' . $row->customer_name . '</td>
                <td>' . $row->order_date . '</td>
                <td>' . $row->total . '</td>
                <td>' . $row->paid . '</td>
                <td>' . $row->due . '</td>
                <td>' . $row->payment_type . '</td>
                   
              <td>
                <a href="invoice_80mm.php?id=' . $row->invoice_id . '" class="btn btn-warning" role="button" target="_blank"><span class="glyphicon glyphicon-print"  style="color:#ffffff" data-toggle="tooltip"  title="列印單據"></span></a>   
              </td>
              <td>
                  <a href="editorder.php?id=' . $row->invoice_id . '" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="編輯訂單"></span></a>     
                      </td>
                      <td>
                  <button id=' . $row->invoice_id . ' class="btn btn-danger btndelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="刪除訂單"></span></button>  
                      </td>
                      </tr>
                      ';
              }
              ?>

            </tbody>
          </table>
        </div>
      </div>
      <!--              </form>-->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  $(document).ready(function() {
    $('#orderListTable').DataTable({
      "order": [
        [0, "desc"]
      ]
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
  $(document).ready(function() {
    $('.btndelete').click(function() {
      let tdh = $(this);
      let id = $(this).attr("id");
      swal({
          title: "確定要刪除此訂單嗎?",
          text: "一旦被刪除便無法回覆!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              url: 'orderdelete.php',
              type: 'post',
              data: {
                pidd: id
              },
              success: function(data) {
                tdh.parents('tr').hide();
              }
            });
            swal("您的訂單已被刪除!", {
              icon: "success",
            });
          } else {
            swal("您的訂單未被刪除!", {
              icon: "success",
              });
          }
        });
    });
  });
</script>
<?php

include_once 'footer.php';

?>