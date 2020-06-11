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
  if(isset($_POST['registerBtn'])){ 
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
                header("refresh:1;dashboard.php");
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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventory POS | 註冊 </title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="register.php"><b>Inventory POS</b>  註冊</a> 
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">由此頁面註冊帳號</p>

    <form action="" method="post">
    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="輸入使用者名稱" name="userName" required>
        <span class="fa fa-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="輸入Email" name="userEmail" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="輸入密碼" name="userPassword" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <a href="index.php">返回登入頁面</a><br>
        </div>
        <div class="col-xs-4">
          <button type="submit" name="registerBtn" class="btn btn-primary btn-block btn-flat">
            確認註冊
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
