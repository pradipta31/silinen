<?php
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include '../config.php';
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SILINEN - RSAD</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../assets/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="../assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <?php if(!empty($additionalCSS)): ?>
        <?php foreach($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
          if($_SESSION['status']!="login"){
            header("location:../index.php?pesan=belum_login");
          }
        ?>
        <header class="main-header">
            <a href="index2.html" class="logo">
                <span class="logo-mini"><b>SI</b>L</span>
                <span class="logo-lg"><b>SI</b>LINEN</span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../assets/images/avatar.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?= $row['username']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="../assets/images/avatar.png" class="img-circle" alt="User Image">
                                    <p>
                                        <?= $row['username']; ?>
                                        <small><?= $row['hak_akses']; ?></small>
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="../logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?>
                    <small><?php echo isset($pageDesc) ? $pageDesc : 'Control Panel'; ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></li>
                </ol>
            </section>
        <?php include __DIR__ . '/navigation.php'; ?>