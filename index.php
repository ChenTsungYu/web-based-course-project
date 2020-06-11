<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="bower_components/sweetalert/sweetalert.js"></script>

<?php
  include_once 'connectdb.php';
  session_start();
  if(isset($_POST['btn_login'])){
    $useremail = $_POST['txt_email'];
    $password =  $_POST['txt_password'];
    // echo $useremail." - ".$password;
    $select= $pdo->prepare("select * from tb_user where userEmail='$useremail'"); 
    $select->execute();
    $row= $select->fetch(PDO::FETCH_ASSOC);
    $hash = (string) $row['password'];
    //check if the password is correct ： password_verify($password, $hash)
    if($row['userEmail']==$useremail AND (password_verify($password, $hash))){
      // echo $success = "login Admin";
      $_SESSION['userID']=$row['userID'];
      $_SESSION['userName']=$row['userName'];
      $_SESSION['userEmail']=$row['userEmail'];
      $_SESSION['role']=$row['role'];
      echo'<script type="text/javascript">
                jQuery(function validation(){
                swal({
                  title: "Hello! '.$_SESSION['userName'].'",
                  text: "登入中.....",
                  icon: "success",
                  button: "OK",
                  });
                });
                </script>';
      header("refresh:1;dashboard.php");
    }
    else{
      echo'<script type="text/javascript">
              jQuery(function validation(){
              swal({
                title: "Error!",
                text: "帳號或密碼錯誤",
                icon: "error",
                button: "Loading.....",
                });
              });
              </script>';
    }
  }   
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventory POS | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>Inventory POS</b>  登入</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">POS 系統 由此登入</p>

    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="輸入Email" name="txt_email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="輸入密碼" name="txt_password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <a href="#" onclick="swal('提醒','請聯絡網站管理員！','warning');">忘記密碼</a><br>
          <a href="register.php">註冊</a><br>
        </div>
        <div class="col-xs-4">
          <button type="submit" name="btn_login" class="btn btn-primary btn-block btn-flat">
            登入
          </button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    
    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->


<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
