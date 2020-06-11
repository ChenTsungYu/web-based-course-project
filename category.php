<?php
  include_once 'connectdb.php';

  session_start();
  if($_SESSION['userEmail']==""){
    header('location:index.php');
}
  include_once "header.php";

  if(isset($_POST['saveBtn'])){
     $category = $_POST['category'];
     echo $category;
      if(empty($category)){
        $error='<script type="text/javascript">
              jQuery(function validation(){
                swal({
                  title: "錯誤!",
                  text: "請填寫欄位!!",
                  icon: "error",
                  button: "Ok",
                });
              });
              </script>';   
        echo $error;  
      }
      if(!isset($error)){
        $insert=$pdo->prepare("insert into tb_category(category) values(:category)");
            
        $insert->bindParam(':category',$category); 
            
        if($insert->execute()){
            echo '<script type="text/javascript">
                jQuery(function validation(){
                swal({
                  title: "Great!",
                  text: "種類新增成功!",
                  icon: "success",
                  button: "Ok",
                });
                });
            </script>';
        }else{
                  echo '<script type="text/javascript">
                  jQuery(function validation(){
                  swal({
                    title: "錯誤",
                    text: "查詢失敗!",
                    icon: "error",
                    button: "Ok",
                  });
                  });
              </script>';      
        }    
      }      
  }//  addBtn end 

  if(isset($_POST['updateBtn'])){
    
     $category = $_POST['category'];
     $id = $_POST['catID'];
     
     if(empty($category)){
           $errorupdate='<script type="text/javascript">
              jQuery(function validation(){
                    swal({
                      title: "錯誤",
                      text: "欄位為空 : 請輸入種類!",
                      icon: "error",
                      button: "OK",
                    });
            });
            </script>';    
       echo $errorupdate; 
     } 
     if(!isset($errorupdate)){ 
           $update=$pdo->prepare("update tb_category set category=:category where catID=".$id);
           $update->bindParam(':category',$category); 
               
           if($update->execute()){
                echo '<script type="text/javascript">
                          jQuery(function validation(){
                          swal({
                            title: "Great!",
                            text: "種類已更新!",
                            icon: "success",
                            button: "OK",
                          });
                        });
                          </script>';
           }else{ 
             echo '<script type="text/javascript">
                  jQuery(function validation(){
                        swal({
                          title: "錯誤!",
                          text: "種類未更新!",
                          icon: "error",
                          button: "Ok",
                        });
                 });
                 </script>';
           }
     } 
  } // btn update code end 

  if(isset($_POST['deleteBtn'])){
          $delete=$pdo->prepare("delete from tb_category where catID=".$_POST['deleteBtn']); 
          if($delete->execute()){
              echo '<script type="text/javascript">
                    jQuery(function validation(){


                  swal({
                    title: "Great!",
                      text: "種類已刪除!",
                      icon: "success",
                      button: "Ok",
                    });


                    });

                    </script>'; 
            
          }else{
              echo '<script type="text/javascript">
                jQuery(function validation(){
                  swal({
                    title: "警告!",
                    text: "種類尚未刪除!",
                    icon: "warning",
                    button: "Ok",
                  });
                });

                </script>';
                      
          } 
  }
?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       種類
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">種類表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
        
      <div class="box-body">
        <form role="form" action="" method="POST">
          <?php
              if(isset($_POST['editBtn'])){
                  $select=$pdo->prepare("select * from tb_category where catID=".$_POST['editBtn']); 
              
                  $select->execute();
   
                  if($select){
                      $row =$select->fetch(PDO::FETCH_OBJ);    
                      echo' <div class="col-md-4">
                                              
                                <div class="form-group">
                                <label >種類</label>
                        <input type="hidden" class="form-control" value="'.$row->catID.'" name="catID"  placeholder="輸入種類" >
                                
                                
                        <input type="text" class="form-control" value="'.$row->category.'" name="category"  placeholder="輸入種類" >
                              </div>
                              
                        <button type="submit" class="btn btn-info" name="updateBtn">更新</button>
                                
                            </div>'; 
                  }
              }else{
                  echo '<div class="col-md-4">
                                 
                      <div class="form-group">
                        <label >種類</label>
                        <input type="text" class="form-control" name="category" placeholder="輸入種類">
                      </div>
                    
                    <button type="submit" class="btn btn-warning" name="saveBtn">儲存</button>
                    
                  </div>';
              }
          
          ?>
              
            <div class="col-md-8">
                   
                <table id="tableCategory" class="table table-striped">
                    <thead>
                      <tr>
                          <th>ID</th>
                          <th>種類 </th>   
                          <th>編輯</th>
                          <th>刪除</th>      
                      </tr>    
                        
                    </thead>    
                  <tbody>
                <?php
                    $select=$pdo->prepare("select * from tb_category order by catID desc");
                    $select->execute();
                      while($row=$select->fetch(PDO::FETCH_OBJ)){
                        
                          echo' <tr>
                              <td>'.$row->catID.'</td>
                              <td>'.$row->category.'</td>
                              
                              <td>
                                <button type="submit" value='.$row->catID.' class="btn btn-success" name="editBtn"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="編輯"></span></button>
                              </td>
                              
                              <td>
                                  <button type="submit" value="'.$row->catID.'" class="btn btn-danger" name="deleteBtn"><span class="glyphicon glyphicon-trash"  title="delete" ></span></button>
                              </td>
                            
                              </tr>';    
                              
                      }            
                    
                  ?>
                  </tbody>               
                </table>           
            </div>
            </form>
          </div>
              <!-- /.box-body -->

              <div class="box-footer">
               
                </div>
        
    </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
  <script>
  $(document).ready( function () {
    $('#tableCategory').DataTable();
} );  
</script>

<?php
 include_once "footer.php";

?>