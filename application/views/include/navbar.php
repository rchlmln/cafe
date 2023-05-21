<?php
$controller = $this->router->fetch_class();
$level = $this->session->userdata('ap_level');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

  <link href='<?php echo config_item('img'); ?>favicon.png' type='image/x-icon' rel='shortcut icon'>
	<title>Cafe Bahagia</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo config_item('font_awesome_adminlte'); ?>css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-bs4/css/dataTables.bootstrap4.min.css"></link>
  <link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-responsive/css/responsive.bootstrap4.min.css"></link>
  <link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-buttons/css/buttons.bootstrap4.min.css"></link>
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo config_item('css_adminlte'); ?>adminlte.min.css">
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	
		
	<!-- jQuery -->
	<script src="<?php echo config_item('plugin'); ?>jquery/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo config_item('plugin'); ?>bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?php echo config_item('plugin'); ?>datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo config_item('plugin'); ?>datatables-buttons/js/buttons.colVis.min.js"></script>
  
  <!-- AdminLTE -->
  <script src="<?php echo config_item('js_adminlte'); ?>adminlte.js"></script>
  

	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?php echo config_item('js_adminlte'); ?>pages/dashboard3.js"></script>
  <!-- <link href="<?php echo config_item('bootstrap'); ?>css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="<?php echo config_item('bootstrap'); ?>css/bootstrap-theme.min.css" rel="stylesheet"> -->
  <link href="<?php echo config_item('font_awesome'); ?>css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo config_item('css'); ?>style-gue.css" rel="stylesheet">
  <!-- <script src="<?php echo config_item('js'); ?>jquery.min.js"></script> -->


</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img class="brand-image img-circle elevation-3" style="opacity: .8" src="<?php echo config_item('img'); ?>cafe_bahagia_mini.png">
      <span class="brand-text font-weight-light">Cafe Bahagia</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <?php if($level == 'admin' OR $level == 'keuangan' OR $level == 'kasir') { ?>
          <li class="nav-item">

            <a href="#" class="nav-link <?php if($controller == 'penjualan') { echo 'active'; } ?>">
              <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
              <p>
                Transaction
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <?php if($level !== 'keuangan'){ ?>
                  <a href="<?php echo site_url('penjualan/transaksi'); ?>" class="nav-link">
                    <p> Transaction </p>
                  </a>
                <?php } ?>
              </li>
              <li class="nav-item">
                <a href="<?php echo site_url('penjualan/history'); ?>" class="nav-link">
                  <p> History Transaction </p>
                </a>
              </li>
              <li class="nav-item"> 
                <a href="<?php echo site_url('penjualan/pelanggan'); ?>" class="nav-link">
                  <p> List Member</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>

          <li class="nav-item">

            <a href="#" class="nav-link <?php if($controller == 'produk') { echo 'active'; } ?>">
              <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
              <p>
                Product
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo site_url('produk'); ?>" class="nav-link">
                  <p> List Product </p>
                </a>
              </li>
              <li class="nav-item"> 
                <a href="<?php echo site_url('produk/list-kategori'); ?>" class="nav-link">
                  <p> List Categori Product</p>
                </a>
              </li>
            </ul>
          </li>

          <?php if($level == 'admin' OR $level == 'keuangan') { ?>
          <li class="nav-item">
            <a href="<?php echo site_url('laporan'); ?>" class="nav-link <?php if($controller == 'laporan') { echo 'active'; } ?>">
              <p> Report </p>
            </a>
          </li>
          <?php } ?>

          <?php if($level == 'admin') { ?>
          <li class="nav-item">
            <a href="<?php echo site_url('user'); ?>" class="nav-link <?php if($controller == 'user') { echo 'active'; } ?>">
              <p> List User </p>
            </a>
          </li>
          <?php } ?>

          <li class="nav-header">
            
          </li>
          <li class="nav-header">
            
          </li>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url('user/ubah-password'); ?>" id='GantiPass' class="nav-link">
              <p> Change Password </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url('secure/logout'); ?>" class="nav-link active" >
              <p> Logout </p>
            </a>
          </li>

        </ul>
      </nav>

      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1 class="m-0">Sale</h1> -->
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a>Hi, <?php echo $this->session->userdata('ap_level_caption'); ?></a></li>

                
              <!-- <li class="breadcrumb-item active">Sale</li> -->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      
              
	

           