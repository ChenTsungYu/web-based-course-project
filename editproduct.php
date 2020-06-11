<?php
include_once 'connectdb.php';

session_start();

if ($_SESSION['userEmail'] == "") {
  header('location:index.php');
}

include_once 'header.php';

$id = $_GET['id'];

$select = $pdo->prepare("select * from tb_product where pid=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['pid'];

$productname_db = $row['pname'];

$category_db = $row['pcategory'];

$purchaseprice_db = $row['purchaseprice'];

$saleprice_db = $row['saleprice'];

$stock_db = $row['pstock'];

$description_db = $row['pdescription'];

$productimage_db = $row['pimage'];


if (isset($_POST['updateBtn'])) {
  $productName = $_POST['productName'];

  $productCategory = $_POST['selectOption'];

  $purchasePrice =  $_POST['productPrice'];

  $salePrice =  $_POST['salePrice'];

  $productStock = $_POST['productStock'];

  $productDescription = $_POST['productDescription'];

  $f_name = $_FILES['myfile']['name'];

  if (!empty($f_name)) {

    $f_tmp = $_FILES['myfile']['tmp_name'];
    $f_size =  $_FILES['myfile']['size'];

    $f_extension = explode('.', $f_name);
    $f_extension = strtolower(end($f_extension));

    $f_newfile =  uniqid() . '.' . $f_extension;

    $store = "productimages/" . $f_newfile;


    if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||  $f_extension == 'png' || $f_extension == 'gif') {
      if ($f_size >= 5000000) {
        $error = '<script type="text/javascript">
              jQuery(function validation(){
                  swal({
                    title: "Error!",
                    text: "Max file should be 1MB!",
                    icon: "warning",
                    button: "Ok",
                  });
              });
          </script>';

        echo $error;
      } else {
        if (move_uploaded_file($f_tmp, $store)) {

          $f_newfile;
          if (!isset($error)) {

            $update = $pdo->prepare("update tb_product set pname=:pname , pcategory=:pcategory , purchaseprice=:pprice , saleprice=:saleprice , pstock=:pstock , pdescription=:pdescription , pimage=:pimage where pid = $id");

            $update->bindParam(':pname', $productName);
            $update->bindParam(':pcategory', $productCategory);
            $update->bindParam(':pprice', $purchasePrice);
            $update->bindParam(':saleprice', $salePrice);
            $update->bindParam(':pstock', $productStock);
            $update->bindParam(':pdescription', $productDescription);
            $update->bindParam(':pimage', $f_newfile);
            if ($update->execute()) {

              echo '<script type="text/javascript">
                  jQuery(function validation(){
                  swal({
                    title: "Great!!",
                    text: "商品更新成功",
                    icon: "success",
                    button: "Ok",
                  });
                });
                </script>';
                
            } else {

              echo '<script type="text/javascript">
                    jQuery(function validation(){
                      swal({
                        title: "ERROR!",
                        text: "商品更新失敗",
                        icon: "error",
                        button: "Ok",
                      });
                    });
                    </script>';
            }
          }
        }
      }
    } else {
      $error = '<script type="text/javascript">
          jQuery(function validation(){
            swal({
              title: "Warning!",
              text: "only jpg ,jpeg, png and gif can be upload!",
              icon: "error",
              button: "Ok",
            });
          });
          </script>';
      echo $error;
    }
  } else {

    $update = $pdo->prepare("update tb_product set pname=:pname , pcategory=:pcategory , purchaseprice=:pprice , saleprice=:saleprice , pstock=:pstock , pdescription=:pdescription , pimage=:pimage where pid = $id");

    $update->bindParam(':pname', $productName);
    $update->bindParam(':pcategory', $productCategory);
    $update->bindParam(':pprice', $purchasePrice);
    $update->bindParam(':saleprice', $salePrice);
    $update->bindParam(':pstock', $productStock);
    $update->bindParam(':pdescription', $productDescription);
    $update->bindParam(':pimage', $productimage_db);

    if ($update->execute()) {
      $msg = '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                          title: "更新成功!",
                          text: "商品資訊已更新",
                          icon: "success",
                          button: "確認",
                        });
                    });

                    </script>';
      echo $msg;
      header('location:productlist.php');
    } else {
      $error = '<script type="text/javascript">
            jQuery(function validation(){
                    swal({
                      title: "Error!",
                      text: "商品資訊更新失敗",
                      icon: "error",
                      button: "Ok",
                    });
              });

              </script>';
      echo $error;
    }
  }
}

$select = $pdo->prepare("select * from tb_product where pid=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['pid'];

$productname_db = $row['pname'];

$category_db = $row['pcategory'];

$purchaseprice_db = $row['purchaseprice'];

$saleprice_db = $row['saleprice'];

$stock_db = $row['pstock'];

$description_db = $row['pdescription'];

$productimage_db = $row['pimage'];

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      編輯商品
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

      <form action="" method="post" name="formproduct" enctype="multipart/form-data">

        <div class="box-body">
          <div class="col-md-6">
            <div class="form-group">
              <label>商品名稱</label>
              <input type="text" class="form-control" name="productName" value="<?php echo $productname_db; ?>" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
              <label>種類</label>
              <select class="form-control" name="selectOption" required>
                <option value="" disabled selected>選擇種類</option>
                <?php
                $select = $pdo->prepare("select * from tb_category order by catid desc");
                $select->execute();
                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                  extract($row);
                ?>
                  <option <?php if ($row['category'] == $category_db) { ?> selected="selected" <?php } ?>>
                    <?php echo $row['category']; ?></option>

                <?php

                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>買入價格</label>
              <input type="number" min="1" step="1" class="form-control" value="<?php echo $purchaseprice_db; ?>" name="productPrice" placeholder="Enter..." required>
            </div>

            <div class="form-group">
              <label>售出價格</label>
              <input type="number" min="1" step="1" class="form-control" value="<?php echo $saleprice_db; ?>" name="salePrice" placeholder="Enter..." required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>庫存</label>
              <input type="number" min="1" step="1" class="form-control" value="<?php echo $stock_db; ?>" name="productStock" placeholder="Enter..." required>
            </div>
            <div class="form-group">
              <label>描述</label>
              <textarea class="form-control" name="productDescription" placeholder="Enter..." rows="4"><?php echo $description_db; ?> </textarea>
            </div>
            <div class="form-group">
              <label>商品圖片</label>
              <img src="productimages/<?php echo $productimage_db; ?>" class="img-responsive" width="50px" height="50px" />
              <input type="file" class="input-group" name="myfile">
              <p>點擊按鈕上傳圖片</p>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-warning" name="updateBtn">更新商品</button>
        </div>
      </form>
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include_once 'footer.php';

?>