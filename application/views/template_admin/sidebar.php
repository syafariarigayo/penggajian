<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-2">Penggajian <sup>Yayasan</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('admin/dashboard') ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Master Data
      </div>

      <!-- Nav Item - Data Unit (Super Admin Only) -->
      <?php if($this->session->userdata('hak_akses') == 1): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('admin/data_unit') ?>">
          <i class="fas fa-fw fa-school"></i>
          <span>Data Unit Pendidikan</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Nav Item - Master Data Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fa fa-fw fa-database"></i>
          <span>Data Pegawai</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manajemen Pegawai:</h6>
            <a class="collapse-item" href="<?php echo base_url('admin/data_pegawai') ?>">
              <i class="fas fa-users"></i> Data Pegawai
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/data_jabatan') ?>">
              <i class="fas fa-briefcase"></i> Data Jabatan
            </a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Transaksi
      </div>

      <!-- Nav Item - Transaksi Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-money-check-alt"></i>
          <span>Penggajian</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Proses Gaji:</h6>
            <a class="collapse-item" href="<?php echo base_url('admin/data_absensi') ?>">
              <i class="fas fa-clipboard-check"></i> Data Absensi
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/penilaian_karyawan') ?>">
              <i class="fas fa-star"></i> Penilaian Karyawan
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/potongan_gaji') ?>">
              <i class="fas fa-minus-circle"></i> Setting Potongan
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/data_penggajian') ?>">
              <i class="fas fa-calculator"></i> Data Gaji
            </a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Laporan
      </div>

      <!-- Nav Item - Laporan Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-copy"></i>
          <span>Laporan & Cetak</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Laporan:</h6>
            <a class="collapse-item" href="<?php echo base_url('admin/laporan_gaji') ?>">
              <i class="fas fa-file-invoice-dollar"></i> Laporan Gaji
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/laporan_absensi') ?>">
              <i class="fas fa-file-alt"></i> Laporan Absensi
            </a>
            <a class="collapse-item" href="<?php echo base_url('admin/penilaian_karyawan/laporan_penilaian') ?>">
              <i class="fas fa-file-chart-line"></i> Laporan Penilaian
            </a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Cetak:</h6>
            <a class="collapse-item" href="<?php echo base_url('admin/slip_gaji') ?>">
              <i class="fas fa-print"></i> Slip Gaji
            </a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Nav Item - Settings -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('ganti_password') ?>">
          <i class="fas fa-fw fa-lock"></i>
          <span>Ubah Password</span></a>
      </li>

      <!-- Nav Item - Logout -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('login/logout')?>">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

      <!-- Sidebar Message (Optional) -->
      <div class="sidebar-card d-none d-lg-flex">
        <i class="fas fa-info-circle"></i>
        <p class="text-center mb-2"><strong>Sistem Penggajian</strong></p>
        <p class="text-center text-white-50" style="font-size: 11px;">
          Yayasan Wakaf Cendekia Takengon
        </p>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Brand -->
          <div class="d-none d-sm-inline-block">
            <h5 class="font-weight-bold mb-0 text-gray-800">
              <i class="fas fa-school text-primary"></i>
              Yayasan Wakaf Cendekia Takengon
            </h5>
          </div>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">
                  <?php 
                  // Count pending items
                  $bulan_ini = date('m').date('Y');
                  $pending_absensi = $this->db->query("SELECT COUNT(DISTINCT p.nik) as total 
                    FROM data_pegawai p 
                    WHERE NOT EXISTS (
                      SELECT 1 FROM data_kehadiran k 
                      WHERE k.nik = p.nik AND k.bulan = '$bulan_ini'
                    )")->row()->total;
                  echo $pending_absensi > 0 ? $pending_absensi : '';
                  ?>
                </span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Notifikasi
                </h6>
                <?php if($pending_absensi > 0): ?>
                <a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('admin/data_absensi')?>">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500"><?php echo date('F Y')?></div>
                    <span class="font-weight-bold"><?php echo $pending_absensi?> pegawai belum input absensi</span>
                  </div>
                </a>
                <?php else: ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Tidak ada notifikasi</a>
                <?php endif; ?>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                  <?php echo $this->session->userdata('nama_pegawai')?>
                  <?php 
                  $hak = $this->session->userdata('hak_akses');
                  if($hak == 1) {
                    echo '<br><small class="text-primary font-weight-bold">Super Admin</small>';
                  } else {
                    echo '<br><small class="text-info font-weight-bold">Admin Unit</small>';
                  }
                  ?>
                </span>
                <img class="img-profile rounded-circle" src="<?php echo base_url('photo/').$this->session->userdata('photo') ?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo base_url('admin/dashboard')?>">
                  <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Dashboard
                </a>
                <a class="dropdown-item" href="<?php echo base_url('ganti_password')?>">
                  <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                  Ubah Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url('login/logout')?>" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">Apakah Anda yakin ingin keluar dari sistem?</div>
              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="<?php echo base_url('login/logout')?>">Logout</a>
              </div>
            </div>
          </div>
        </div>