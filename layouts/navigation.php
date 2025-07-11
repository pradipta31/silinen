<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../assets/images/avatar.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $row['username']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <!-- MENU UNTUK ADMIN -->
        <?php
            if ($row['hak_akses'] == 'admin') {
        ?>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= $_SESSION['active_menu'] == 'dashboard' ? 'active' : '' ?>">
                <a href="../admin/">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="treeview <?= $_SESSION['active_menu'] == 'pengguna' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Pengguna</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../admin/tambah_pengguna.php"><i class="fa fa-plus"></i> Tambah Pengguna Baru</a></li>
                    <li><a href="../admin/data_pengguna.php"><i class="fa fa-arrow-circle-right"></i> Data Pengguna</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-building"></i> <span>Ruangan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-plus"></i> Tambah Ruangan Baru</a></li>
                    <li><a href="#"><i class="fa fa-arrow-circle-right"></i> Data Ruangan</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bed"></i> <span>Linen</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-plus"></i> Tambah Linen Baru</a></li>
                    <li><a href="#"><i class="fa fa-arrow-circle-right"></i> Data Linen</a></li>
                </ul>
            </li>
            <li class="header">REPORT</li>
            <li><a href="#"><i class="fa fa-book"></i> <span>Laporan</span></a></li>
        </ul>

        <!-- MENU UNTUK ADMIN RUANGAN -->
        <?php
            }else if($row['hak_akses'] == 'admin_ruangan'){
        ?>

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bed"></i> <span>Linen</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-plus"></i> Tambah Linen Baru</a></li>
                    <li><a href="#"><i class="fa fa-arrow-circle-right"></i> Data Linen</a></li>
                </ul>
            </li>
            <li class="header">REPORT</li>
            <li><a href="#"><i class="fa fa-book"></i> <span>Laporan</span></a></li>
        </ul>

        <!-- MENU UNTUK PETUGAS LAUNDRY -->
        <?php
            }else if($row['hak_akses'] == 'admin_ruangan'){
        ?>

        <!-- MENU UNTUK KEPALA PENANGGUNG JAWAB -->
        <?php
            }else if($row['hak_akses'] == 'admin_ruangan'){
        ?>

        <?php
            }
        ?>

    </section>
    <!-- /.sidebar -->
</aside>