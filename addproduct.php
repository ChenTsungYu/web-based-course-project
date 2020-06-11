<?php
include_once 'connectdb.php';

session_start();
if($_SESSION['userEmail']==""){
    header('location:index.php');
}
include_once 'header.php';

    if(isset($_POST['addProductBtn'])){
          
            $productName = $_POST['productName'];
            
            $productCategory= $_POST['selectOption']; 
                
            $purchasePrice =  $_POST['productPrice']; 
                
            $salePrice =  $_POST['salePrice']; 
                
            $productStock= $_POST['productStock']; 

            $productDescription=$_POST['productDescription'];

            $f_name= $_FILES['myfile']['name'];

            $f_tmp = $_FILES['myfile']['tmp_name'];

            $f_size =  $_FILES['myfile']['size'];
                
            $f_extension = explode('.',$f_name);
            $f_extension= strtolower(end($f_extension));
                
            $f_newfile =  uniqid().'.'. $f_extension;   
              
            $store = "productimages/".$f_newfile; // 存放的地方
                
          if($f_extension=='jpg' || $f_extension=='jpeg' ||  $f_extension=='png' || $f_extension=='gif'){
    
            if($f_size>=5000000 ){
                $error= '<script type="text/javascript">
                            jQuery(function validation(){
                            swal({
                              title: "Error!",
                              text: "Max file should be 5MB!",
                              icon: "warning",
                              button: "Ok",
                            });
                        });
                        </script>';
                  echo $error;      
            }else{
                  if(move_uploaded_file($f_tmp,$store)){
                          $productImage=$f_newfile;
                            if(!isset($error)){
                                $insert=$pdo->prepare("insert into tb_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription,pimage) values(:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription,:pimage)"); 

                                $insert->bindParam(':pname',$productName); 
                                $insert->bindParam(':pcategory',$productCategory);
                                $insert->bindParam(':purchaseprice',$purchasePrice);
                                $insert->bindParam(':saleprice',$salePrice);
                                $insert->bindParam(':pstock',$productStock);
                                $insert->bindParam(':pdescription',$productDescription);
                                $insert->bindParam(':pimage',$productImage);
                                echo "Before execute insert!!!!!!!";
                                      if($insert->execute()){
                                      
                                        echo'<script type="text/javascript">
                                          jQuery(function validation(){
                                                swal({
                                                  title: "成功!",
                                                  text: "商品新增完成",
                                                  icon: "success",
                                                  button: "Ok",
                                                });
                                          });
                                          </script>';  
                                      }else{  
                                          echo'<script type="text/javascript">
                                                      jQuery(function validation(){
                                                      swal({
                                                        title: "錯誤!",
                                                        text: "商品新增失敗",
                                                        icon: "error",
                                                        button: "Ok",
                                                      });
                                                  });
                                              </script>';  
                                                  
                                      }     
                            }  
                  } 
            }   
          }else{
              $error= '<script type="text/javascript">
                  jQuery(function validation(){
                  swal({
                    title: "Warning!",
                    text: "檔案限定 jpg ,jpeg, png and gif 可被上傳!",
                    icon: "error",
                    button: "Ok",
                  });
                  });

                  </script>';
                        
              echo $error;      
          }    
    }
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
     新增商品
        <small></small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <!-- /.box-header -->
            <!-- form start -->
         <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"> <a href="productlist.php" class="btn btn-primary" role="button">回到商品列表</a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

          <form action="" method="post"  name="formProduct" enctype="multipart/form-data" >
            <div class="box-body">
                <div class="col-md-6">
                    
                      <div class="form-group">
                          <label >商品名稱</label>
                          <input type="text" class="form-control" name="productName" placeholder="輸入商品名稱" required>
                      </div>        
                <div class="form-group">
                  <label>種類</label>
                  <select class="form-control" name="selectOption" required>
                    <option value="" disabled selected>選擇種類</option>
                        <?php
                            $select = $pdo->prepare("select * from tb_category order by catID desc");          
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_ASSOC)){
                                      extract($row);
                                      ?>    
                                          <option>
                                            <?php echo $row['category'];?>
                                          </option>
                            <?php   }     ?>    
                  </select>
                </div>                     
                 <div class="form-group">
                      <label >買入價格</label>
                      <input type="number" min="1" step="1" class="form-control" name="productPrice" placeholder="輸入買入價格" required>
                  </div>
                
                  <div class="form-group">
                        <label >售出價格</label>
                        <input type="number" min="1" step="1" class="form-control" name="salePrice" placeholder="輸入銷售價格" required>
                  </div>  
            </div> 
                  <div class="col-md-6">
                        <div class="form-group">
                          <label >庫存</label>
                          <input type="number" min="1" step="1" class="form-control" name="productStock" placeholder="輸入庫存" required>
                        </div> 
                        <div class="form-group">
                            <label >描述</label>
                            <textarea class="form-control" name="productDescription" placeholder="輸入詳情"  rows="4"></textarea>
                          </div>
                        <div class="form-group">
                                  <label >商品圖片</label>
                                  <input type="file" class="input-group" name="myfile"  required>
                                  <p>上傳圖片</p>
                        </div>    
                  </div>      
        
             </div>
                <div class="box-footer">
                      <button type="submit" class="btn btn-info" name="addProductBtn">新增</button>            
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