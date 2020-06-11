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
            商品列表
            <small></small>
          </h1>
         
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">商品列表</h3>
            </div>

            <div class="box-body">
              <div style="overflow-x:auto;">

                <table id="productTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>訂單</th>
                      <th>商品名稱</th>
                      <th>種類</th>
                      <th>買入價格</th>
                      <th>售出價格</th>
                      <th>庫存</th>
                      <th>描述</th>
                      <th>圖片</th>
                      <th>檢視</th>
                      <th>編輯</th>
                      <th>刪除</th>
                    </tr>

                  </thead>
                  <tbody>

                    <?php
                    $select = $pdo->prepare("select * from tb_product  order by pid desc");

                    $select->execute();

                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                      echo '
                                              <tr>
                                              <td>' . $row->pid . '</td>
                                              <td>' . $row->pname . '</td>
                                              <td>' . $row->pcategory . '</td>
                                              <td>' . $row->purchaseprice . '</td>
                                              <td>' . $row->saleprice . '</td>
                                              <td>' . $row->pstock . '</td>
                                              <td>' . $row->pdescription . '</td>
                                              <td><img src = "productimages/' . $row->pimage . '" class="img-rounded" width="40px" height="40px"/></td>
                                              
                                              <td>
                                              <a href="viewproduct.php?id=' . $row->pid . '" class="btn btn-success" role="button"><span class="glyphicon glyphicon-eye-open"  style="color:#ffffff" data-toggle="tooltip"  title="檢視"></span></a>   
                                                  
                                                  </td>
                                        <td>
                                            <a href="editproduct.php?id=' . $row->pid . '" class="btn btn-info" role="button"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="編輯"></span></a>   
                                    
                                              </td>
                                              
                                              <td>
                                            <button id=' . $row->pid . ' class="btn btn-danger deleteBtn" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="刪除"></span></button>  
                                                </td>
                                                </tr>
                                                ';
                    }
                    ?>

                  </tbody>
                </table>
              </div>

            </div>

          </div>
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <script>
        $(document).ready(function() {
          $('#productTable').DataTable({

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
      </script>
      <script>
        $(document).ready(function() {
          $('.deleteBtn').click(function() {
            var tdh = $(this);
            var id = $(this).attr("id"); // 取得該品項的ID
            swal({
                title: "確定要刪除嗎?",
                text: "商品一經刪除便無法復原!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {

                  $.ajax({
                    url: 'productdelete.php',
                    type: 'post',
                    data: {
                      pidd: id
                    },
                    success: function(data) {
                      tdh.parents('tr').hide();
                    }
                  });
                  swal("商品已成功刪除!", {
                    icon: "success",
                  });
                } else {
                  swal("此商品未刪除!");
                }
              });
          });
        });
      </script>
      <?php
      include_once 'footer.php';
      ?>