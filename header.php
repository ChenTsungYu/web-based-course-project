<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POS 系統</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- jQuery 3 -->
      <script src="bower_components/jquery/dist/jquery.min.js"></script>
      <!-- Bootstrap 3.3.7 -->
      <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.min.js"></script>

      <script src="bower_components/sweetalert/sweetalert.js"></script>
      <!-- DataTables -->
      <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
      <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
      <!-- Chart.js -->
      <script src="Chart.js-2.8.0/dist/Chart.min.js"></script>
      
      <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
      <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
      <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
      <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
       
      <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

        <!-- Google Font -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Select2 -->
 <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
        <!-- DataTables -->
      <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">   
  <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">  
                                             
  <!-- iCheck 1.0.1 -->
  <script src="plugins/iCheck/icheck.min.js"></script>                         
                                                
  <!-- Select2 -->
  <script src="bower_components/select2/dist/js/select2.full.min.js"></script>       
  </head>

  </head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>P</b>OS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>存貨</b>-POS</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
 
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="dist/img/avator.jpeg" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['userName'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="dist/img/avator.jpeg" class="img-circle" alt="User Image">

                <p>
                <?php echo $_SESSION['userEmail'];?>
                <br/>
                <?php echo $_SESSION['userName'];?>, 您好

                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="changePassword.php" class="btn btn-default btn-flat">修改密碼</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">登出</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/avator.jpeg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p style="color: white;" >歡迎 - <?php echo $_SESSION['userName'];?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> 在線</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
       
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>首頁</span></a></li>
        <li><a href="category.php"><i class="fa fa-link"></i> <span>種類</span></a></li>
        <li><a href="addproduct.php"><i class="fa fa-product-hunt"></i> <span>商品新增</span></a></li>
          
          <li><a href="productlist.php"><i class="fa fa-th-list"></i> <span>商品列表</span></a></li>

          <li><a href="createorder.php"><i class="fa fa-first-order"></i> <span>建立訂單</span></a></li>
            
          <li><a href="orderlist.php"><i class="fa fa-list-ul"></i> <span>訂單列表</span></a></li>
          <li class="treeview">
                <a href="#">
                  <i class="fa fa-table"></i> <span>銷售報表</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="tablereport.php"><i class="fa fa-circle-o"></i>銷售列表</a></li>
                  <li><a href="graphreport.php"><i class="fa fa-circle-o"></i>視覺報表</a></li>
                </ul>
            </li>
            <?php
                 if($_SESSION['role'] == "Admin") echo '<li><a href="registration.php"><i class="fa fa-registered"></i> <span>註冊列表</span></a></li>';
            ?>
         
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>