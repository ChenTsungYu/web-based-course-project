<?php
  
  include_once 'connectdb.php';
  session_start();
  if($_SESSION['userEmail']=="") header('location:index.php');
include_once 'header.php';
       
  if(isset($_POST['btn_update'])){
      $oldPassword = $_POST["oldpwd"];
      $newPassword = $_POST["newpwd"];
      $confPassword = $_POST["confpwd"];
      $email=$_SESSION['userEmail'];
      $select=$pdo->prepare("select * from tb_user where userEmail='$email'");

      $select->execute();
      $row=$select->fetch(PDO::FETCH_ASSOC);
      $userEmail_db= $row['userEmail'];
      $password_db= $row['password'];     
    //   echo $row['userEmail']."-".$row['userName'];
    //  echo $oldPassword."-".$newPassword."-".$confPassword;
    //  echo $userEmail_db."-".$password_db;
    // ===========  confirm input value is same as value in DB   ======================
    if(password_verify($oldPassword, $password_db)){
      // echo "success!";
      if($newPassword == $confPassword){
        // echo "match!";
        $update=$pdo->prepare("update tb_user set password=:pass where userEmail=:email");
        $update->bindParam(':pass',password_hash($confPassword, PASSWORD_DEFAULT)); 
        $update->bindParam(':email',$email);  
              if($update->execute()){
                  echo'<script type="text/javascript">
                        jQuery(function validation(){
                        swal({
                          title: "Great!",
                          text: "您的密碼更新成功！",
                          icon: "success",
                          button: "Ok",
                        });
                      });
                      </script>';
              }else{
                echo'<script type="text/javascript">
                jQuery(function validation(){
                        swal({
                          title: "錯誤 !!",
                          text: "Query Fail",
                          icon: "error",
                          button: "Ok",
                        });
                    });
                </script>';
              }
      }else{
            echo'<script type="text/javascript">
            jQuery(function validation(){
            swal({
                    title: "Oops!!!",
                    text: "您的新密碼與密碼確認不符，請重新輸入",
                    icon: "warning",
                    button: "Ok",
                  });
                });
              </script>';
      }
    }else{
      echo'<script type="text/javascript">
      jQuery(function validation(){
      
      
      swal({
        title: "Warning !!",
        text: "您的新密碼與舊密碼不符，請重新輸入！",
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
          修改密碼
        </h1>
        
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">由下方表單修改密碼</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="POST">
              <div class="box-body">
            
                <div class="form-group">
                  <label for="inputOldPassword">舊密碼</label>
                  <input type="text" class="form-control" id="inputOldPassword" placeholder="舊密碼" name="oldpwd" required>
                </div>
                <div class="form-group">
                  <label for="inputNewPassword">新密碼</label>
                  <input type="password" class="form-control" id="inputNewPassword" placeholder="新密碼" name="newpwd" required>
                </div>
                <div class="form-group">
                  <label for="inputConfPassword">確認密碼</label>
                  <input type="password" class="form-control" id="inputConfPassword" placeholder="確認密碼" name="confpwd" required>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="btn_update">確認修改</button>
              </div>
            </form>
          </div>

    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
<?php
 include_once "footer.php";
?>

