<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apotek | <?= $page; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets') ?>/img/users/favicon.png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets') ?>/bower_components/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- jsBarcode -->
    <script src="<?= base_url('assets') ?>/bower_components/jsBarcode/JsBarcode.all.min.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- DateRange Picker -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/daterangepicker/daterangepicker.css">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?= base_url('assets') ?>/img/users/favicon.png" alt="AdminLTELogo" height="60" width="60">
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <?php if ($this->fungsi->user_login()->avatar == null) { ?>
                            <img src="<?= base_url('assets') . '/dist/img/user2-160x160.jpg' ?>" class="user-image img-circle elevation-2" alt="User Image">
                        <?php } else { ?>
                            <img src="<?= base_url('assets/img/users/') . $this->fungsi->user_login()->avatar  ?>" class="user-image img-circle elevation-2" alt="User Image">
                        <?php } ?>
                        <span class="d-none d-md-inline"><?= ucfirst($this->fungsi->user_login()->name) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <?php if ($this->fungsi->user_login()->avatar == null) { ?>
                                <img src="<?= base_url('assets') . '/dist/img/user2-160x160.jpg' ?>" class="user-image img-circle elevation-2" alt="User Image">
                            <?php } else { ?>
                                <img src="<?= base_url('assets/img/users/') . $this->fungsi->user_login()->avatar  ?>" class="user-image img-circle elevation-2" alt="User Image">
                            <?php } ?>
                            <p>
                                <?= ucfirst($this->fungsi->user_login()->name) ?> - <?= ucfirst($this->fungsi->user_login()->level == 1 ? 'Admin' : 'Karyawan') ?>
                                <small><?= ucfirst($this->fungsi->user_login()->address ?? '-') ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="<?= site_url('auth/logout') ?>" class="btn btn-default btn-flat float-right">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= site_url('dashboard') ?>" class="brand-link">
                <img src="<?= base_url('assets') ?>/img/users/favicon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Apotek</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <?php if ($this->fungsi->user_login()->avatar == null) { ?>
                            <img src="<?= base_url('assets') . '/dist/img/user2-160x160.jpg' ?>" class="user-image img-circle elevation-2" alt="User Image">
                        <?php } else { ?>
                            <img src="<?= base_url('assets/img/users/') . $this->fungsi->user_login()->avatar  ?>" class="user-image img-circle elevation-2" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= ucfirst($this->fungsi->user_login()->name) ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header">UTAMA</li>
                        <li class="nav-item">
                            <a href="<?= site_url('dashboard') ?>" <?= $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('supplier') ?>" <?= $this->uri->segment(1) == 'supplier' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                <i class="nav-icon fas fa-truck"></i>
                                <p>
                                    Pemasok
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('customer') ?>" <?= $this->uri->segment(1) == 'customer' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Pelanggan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item <?= $this->uri->segment(1) == 'category' || $this->uri->segment(1) == 'satuan' || $this->uri->segment(1) == 'item' || $this->uri->segment(1) == 'type' || $this->uri->segment(1) == 'location' ? 'menu-is-opening menu-open' : '' ?>">
                            <a href="#" class="nav-link <?= $this->uri->segment(1) == 'category' || $this->uri->segment(1) == 'satuan' || $this->uri->segment(1) == 'item' || $this->uri->segment(1) == 'type' || $this->uri->segment(1) == 'location' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>
                                    Produk
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('type') ?>" <?= $this->uri->segment(1) == 'type' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tipe</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('category') ?>" <?= $this->uri->segment(1) == 'category' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kategori</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('satuan') ?>" <?= $this->uri->segment(1) == 'satuan' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Satuan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('location') ?>" <?= $this->uri->segment(1) == 'location' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Lokasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('item') ?>" <?= $this->uri->segment(1) == 'item' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Item</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item <?= $this->uri->segment(1) == 'stok' || $this->uri->segment(1) == 'opname' || $this->uri->segment(1) == 'history_opname' ? 'menu-is-opening menu-open' : '' ?>">
                            <a href="#" class="nav-link <?= $this->uri->segment(1) == 'stok' || $this->uri->segment(1) == 'opname' || $this->uri->segment(1) == 'history_opname' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>
                                    Persediaan
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('stok') ?>" <?= $this->uri->segment(1) == 'stok' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Produk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('opname') ?>" <?= $this->uri->segment(1) == 'opname' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Stok Opname</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('history_opname') ?>" <?= $this->uri->segment(1) == 'history_opname' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Riwayat Stok Opname</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('stokin') ?>" <?= $this->uri->segment(1) == 'stokin' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                <i class="nav-icon fas fa-truck-loading"></i>
                                <p>
                                    Pembelian
                                </p>
                            </a>
                        </li>
                        <li class="nav-item <?= $this->uri->segment(1) == 'sales' || $this->uri->segment(1) == 'sales_report' || $this->uri->segment(1) == 'sales_retur' ? 'menu-is-opening menu-open' : '' ?>">
                            <a href="#" class="nav-link <?= $this->uri->segment(1) == 'sales' || $this->uri->segment(1) == 'sales_report' || $this->uri->segment(1) == 'sales' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cash-register"></i>
                                <p>
                                    Penjualan
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('sales') ?>" <?= $this->uri->segment(1) == 'sales' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kasir</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('sales_report') ?>" <?= $this->uri->segment(1) == 'sales_report' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Penjualan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if ($this->fungsi->user_login()->level == 1) {  ?>
                            <li class="nav-header">PENGATURAN</li>
                            <li class="nav-item">
                                <a href="<?= site_url('user') ?>" <?= $this->uri->segment(1) == 'user' || $this->uri->segment(1) == '' ? 'class="nav-link active"' : 'class="nav-link"' ?>>
                                    <i class="nav-icon fas fa-user-cog"></i>
                                    <p>
                                        Pengaturan Pengguna
                                    </p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?= $contents ?>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 2.1
            </div>
            <strong>Copyright &copy; 2023 <a href="https://github.com/billz444">Eqtada Bilhadi</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/jszip/jszip.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Select2 -->
    <script src="<?= base_url('assets') ?>/plugins/select2/js/select2.full.min.js"></script>
</body>

</html>