<?php
  include_once 'connectdb.php';
  session_start();
  if($_SESSION['userEmail']=="") header('location:index.php');
  if($_SESSION['role']=="User") header('location:dashboard.php');
  include_once "header.php";
  // 不顯示錯誤
  error_reporting(0);
   // 依照網址後面(?符號)查詢的參數取得對應的值 e.g. http://www.yourdomain.com?id=1&name=Taylor&city=London
   if($_GET['id'] ){
     if($_SESSION['userEmail']=="tom@gmail.com"){
          $id = $_GET['id'];
          $delete = $pdo -> prepare("delete from tb_user where userID=".$id);
          if($delete->execute()){
            echo'<script type="text/javascript">
            jQuery(function validation(){
                swal({
                  title: "Great!",
                  text: "帳號已刪除 !!",
                  icon: "success",
                  button: "Ok",
                });
            });
            </script>';
          }
          header('location:registration.php');
     }else{
      echo'<script type="text/javascript">
          jQuery(function validation(){
              swal({
                title: "警告!",
                text: "您未擁有刪除帳號的權限 !!",
                icon: "warning",
                button: "Ok",
              });
          });
          </script>';
    }
  }
  if(isset($_POST['saveBtn'])){ 
      $userName=$_POST['userName'];
      $userEmail=$_POST['userEmail'];
      $password = password_hash($_POST['userPassword'], PASSWORD_DEFAULT);
      $userRole="User";
      // echo $userName."-".$userEmail."-".$password."-".$userRole;

      if(isset($_POST['userEmail'])){
        $select=$pdo->prepare("select userEmail from tb_user where userEmail='$userEmail'"); 
        $select->execute();
        if($select->rowCount() > 0){
              echo'<script type="text/javascript">
                  jQuery(function validation(){
                  swal({
                    title: "Warning!",
                    text: "Email 已存在，請換Email進行註冊!!",
                    icon: "warning",
                    button: "Ok",
                  });
                });
              </script>';
        }else{
                $insert=$pdo->prepare("insert into tb_user(userName,userEmail,password,role) values(:name,:email,:pass,:role)"); 
              
                $insert->bindParam(':name',$userName);
                $insert->bindParam(':email',$userEmail);
                $insert->bindParam(':pass',$password);
                $insert->bindParam(':role',$userRole);
            
                if($insert->execute()){
                  echo'<script type="text/javascript">
                        jQuery(function validation(){
                        swal({
                          title: "Great!",
                          text: "註冊成功！！",
                          icon: "success",
                          button: "Ok",
                        });
                      });
                    </script>';
                }else{
                  echo'<script type="text/javascript">
                          jQuery(function validation(){
                          swal({
                            title: "Error!",
                            text: "註冊失敗！！",
                            icon: "error",
                            button: "Ok",
                            });
                          });
                      </script>';
                }
        }
      }
  }

?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      註冊管理
        <small></small>
      </h1>
      
    </section>

    <!-- Main content -->
  <section class="content container-fluid">

    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">註冊表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
        <form role="form" action="" method="POST">
          <div class="box-body">
              
              <div class="col-md-4">
                                 
                  <div class="form-group">
                    <label >姓名</label>
                    <input type="text" class="form-control" name="userName" placeholder="輸入名稱" required>
                  </div>
                                 
                                 
                <div class="form-group">
                  <label >Email </label>
                  <input type="email" class="form-control" name="userEmail" placeholder="輸入email" required>
                </div>
                <div class="form-group">
                  <label >密碼</label>
                  <input type="password" class="form-control" name="userPassword" placeholder="輸入密碼" required>
                </div>
               
                <!-- <div class="form-group">
                  <label>Role</label>
                  <select class="form-control" name="roleOption" required>
                    <option value="" disabled selected>Select role</option>
                   <option value="User">User</option>
                     <option value="Admin">Admin</option>
                    
                  </select>
                </div> -->
                
                 <button type="submit" class="btn btn-info" name="saveBtn">儲存</button>
                 
              </div>
            <div class="col-md-8">
                   
                <table class="table table-striped">
                    <thead>
                      <tr>
                      <th>註冊順序</th>
                      <th>姓名</th>   
                        <th>Email</th>   
                        <th>密碼</th>   
                          <th>角色</th>
                          <th>刪除</th>      
                      </tr>    
                        
                    </thead>    
                  <tbody>
                          <?php
                              $select=$pdo->prepare("select * from tb_user  order by userID asc"); # desc end / asc end
                                      
                              $select->execute();
                                      
                              while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                                      $str = '
                                      <tr>
                                      <td>'.$row->userID.'</td>
                                      <td>'.$row->userName.'</td>
                                      <td>'.$row->userEmail.'</td>
                                      <td class="pwd">'.$row->password.'</td>
                                      <td>'.$row->role.'</td>
                                      <td>';
                                        if($_SESSION['userEmail'] =="tom@gmail.com"){
                                          $str = $str.'<a href="registration.php?id='.$row->userID.'" class="btn btn-danger" role="button">
                                          <span class="glyphicon glyphicon-trash"  title="delete" name="deleteBtn"></span></a>';
                                        }
                                        $str=$str.
                                        '</td>
                                      </tr>
                                      ';
                                      echo $str;
                                  }          
                            ?>      
                  </tbody>               
                </table>           
            </div>
          </div>
              <!-- /.box-body -->

              <div class="box-footer">
               
                </div>
        </form>
    </div>
       
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
<script>
(function(){
      const len = 10; // 預設顯示的字數
      const content = document.querySelectorAll(".pwd"); // content 獲取內容 div 物件
      // console.log("content: ", content[0].innerHTML);
      for (let i = 0; i < content.length; i++) {
        let text = content[i].innerHTML;  // text 為內容
        content[i].innerHTML = `${text.substring(0,len)}......`; // 內容為 text 的前 len 個(substring)提取的字元
      }  
})();
</script>
<?php
 include_once "footer.php";

?>