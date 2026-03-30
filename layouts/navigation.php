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
            <li class="<?= $_SESSION['active_menu'] == 'ruangan' ? 'active' : '' ?>">
                <a href="../admin/data_ruangan.php">
                    <i class="fa fa-building"></i> <span>Ruangan</span>
                </a>
            </li>
            <li class="<?= $_SESSION['active_menu'] == 'linen' ? 'active' : '' ?>">
                <a href="../admin/data_linen.php">
                    <i class="fa fa-bed"></i> <span>Data Linen</span>
                </a>
            </li>
            <li class="<?= $_SESSION['active_menu'] == 'linen_ruangan' ? 'active' : '' ?>">
                <a href="../admin/linen_ruangan.php">
                    <i class="fa fa-bed"></i> <span>Linen Ruangan</span>
                </a>
            </li>
            <li class="treeview <?= $_SESSION['active_menu'] == 'distribusi' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-tint"></i> <span>Distribusi</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../admin/data_pengajuan.php"><i class="fa fa-paper-plane"></i> Pengajuan Linen</a></li>
                    <li><a href="../admin/data_pencucian.php"><i class="fa fa-tint"></i> Permohonan Pencucian</a></li>
                    <!-- <li><a href="../admin/linen_bersih.php"><i class="fa fa-check-circle"></i> Linen Bersih</a></li>
                    <li><a href="../admin/linen_kotor.php"><i class="fa fa-exclamation-circle"></i> Linen Kotor</a></li> -->
                </ul>
            </li>
            <li class="header">REPORT</li>
            <li class="treeview <?= $_SESSION['active_menu'] == 'laporan' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-file-text-o"></i> <span>Laporan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../admin/data_laporan_pengajuan.php"><i class="fa fa-file-text-o"></i> Laporan Pengajuan Linen</a></li>
                    <li><a href="../admin/data_laporan_pencucian.php"><i class="fa fa-file-text-o"></i> Laporan Pencucian Linen</a></li>
                </ul>
            </li>
        </ul>

        <!-- MENU UNTUK ADMIN RUANGAN -->
        <?php
            }else if($row['hak_akses'] == 'admin_ruangan'){
        ?>

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= $_SESSION['active_menu'] == 'dashboard' ? 'active' : '' ?>">
                <a href="../admin_ruangan/">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview <?= $_SESSION['active_menu'] == 'linen' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-bed"></i> <span>Linen</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <!-- <li><a href="../admin_ruangan/data_linen.php"><i class="fa fa-bed"></i> Data Linen</a></li> -->
                    <li><a href="../admin_ruangan/data_pengajuan.php"><i class="fa fa-paper-plane"></i> Pengajuan Linen</a></li>
                    <li><a href="../admin_ruangan/linen_ruangan.php"><i class="fa fa-bed"></i> Linen Ruangan</a></li>
                    <li><a href="../admin_ruangan/data_pencucian.php"><i class="fa fa-tint"></i> Permohonan Pencucian</a></li>
                </ul>
            </li>
            <li class="header">REPORT</li>
            <li><a href="../admin/data_laporan.php"><i class="fa fa-book"></i> <span>Laporan</span></a></li>
        </ul>

        <!-- MENU UNTUK PETUGAS LAUNDRY -->
        <?php
            }else if($row['hak_akses'] == 'petugas_laundry'){
        ?>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= $_SESSION['active_menu'] == 'dashboard' ? 'active' : '' ?>">
                <a href="../petugas_laundry/">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="treeview <?= $_SESSION['active_menu'] == 'distribusi' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-tint"></i> <span>Distribusi</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../petugas_laundry/data_pengajuan.php"><i class="fa fa-paper-plane"></i> Pengajuan Linen</a></li>
                    <li><a href="../petugas_laundry/data_pencucian.php"><i class="fa fa-tint"></i> Permohonan Pencucian</a></li>
                    <!-- <li><a href="../petugas_laundry/linen_bersih.php"><i class="fa fa-check-circle"></i> Linen Bersih</a></li>
                    <li><a href="../petugas_laundry/linen_kotor.php"><i class="fa fa-exclamation-circle"></i> Linen Kotor</a></li> -->
                </ul>
            </li>
        </ul>

        <!-- MENU UNTUK KEPALA PENANGGUNG JAWAB -->
        <?php
            }else if($row['hak_akses'] == 'kepala_penanggung_jawab'){
        ?>

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= $_SESSION['active_menu'] == 'dashboard' ? 'active' : '' ?>">
                <a href="../admin/">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            
            <li class="<?= $_SESSION['active_menu'] == 'pengguna' ? 'active' : '' ?>">
                <a href="../kepala_penanggung_jawab/data_pengguna.php">
                    <i class="fa fa-users"></i> <span>Pengguna</span>
                </a>
            </li>
            <li class="<?= $_SESSION['active_menu'] == 'ruangan' ? 'active' : '' ?>">
                <a href="../kepala_penanggung_jawab/data_ruangan.php">
                    <i class="fa fa-building"></i> <span>Ruangan</span>
                </a>
            </li>
            <li class="<?= $_SESSION['active_menu'] == 'linen' ? 'active' : '' ?>">
                <a href="../kepala_penanggung_jawab/data_linen.php">
                    <i class="fa fa-bed"></i> <span>Data Linen</span>
                </a>
            </li>
            <li class="<?= $_SESSION['active_menu'] == 'linen_ruangan' ? 'active' : '' ?>">
                <a href="../kepala_penanggung_jawab/linen_ruangan.php">
                    <i class="fa fa-bed"></i> <span>Linen Ruangan</span>
                </a>
            </li>
            <li class="treeview <?= $_SESSION['active_menu'] == 'distribusi' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-tint"></i> <span>Distribusi</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../kepala_penanggung_jawab/data_pengajuan.php"><i class="fa fa-paper-plane"></i> Pengajuan Linen</a></li>
                    <li><a href="../kepala_penanggung_jawab/data_pencucian.php"><i class="fa fa-tint"></i> Permohonan Pencucian</a></li>
                </ul>
            </li>
            <li class="header">REPORT</li>
            <li class="treeview <?= $_SESSION['active_menu'] == 'laporan' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-file-text-o"></i> <span>Laporan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="../kepala_penanggung_jawab/data_laporan_pengajuan.php"><i class="fa fa-file-text-o"></i> Laporan Pengajuan Linen</a></li>
                    <li><a href="../kepala_penanggung_jawab/data_laporan_pencucian.php"><i class="fa fa-file-text-o"></i> Laporan Pencucian Linen</a></li>
                </ul>
            </li>
        </ul>

        <?php
            }
        ?>

    </section>
    <!-- /.sidebar -->
</aside>