<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-tachometer-alt"></i> <?php echo $title?>
    </h1>
    
    <!-- Date Display -->
    <div class="text-right">
      <span class="text-muted"><i class="far fa-calendar-alt"></i></span>
      <span id="date" class="font-weight-bold"></span>
      <script>
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear()
        document.getElementById("date").innerHTML = day + " " + months[month] + " " + year;
      </script>
    </div>
  </div>

  <!-- Filter Unit (Super Admin Only) -->
  <?php if($hak_akses == 1): ?>
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-gradient-primary">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-filter"></i> Filter Unit Pendidikan
      </h6>
    </div>
    <div class="card-body">
      <form method="GET" class="form-inline">
        <div class="form-group mr-3">
          <label class="mr-2"><i class="fas fa-school"></i> Pilih Unit:</label>
          <select name="unit" class="form-control" id="unitFilter" onchange="this.form.submit()">
            <option value="ALL" <?php echo ($selected_unit == 'ALL') ? 'selected' : ''; ?>>
              📊 Semua Unit Yayasan
            </option>
            <?php foreach($units as $u): ?>
            <option value="<?php echo $u->kode_unit?>" <?php echo ($selected_unit == $u->kode_unit) ? 'selected' : ''; ?>>
              <?php echo $u->kode_unit?> - <?php echo $u->nama_unit?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fas fa-search"></i> Tampilkan
        </button>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- Info Alert -->
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fas fa-info-circle"></i>
    <strong>Menampilkan Data:</strong> <?php echo $nama_unit?>
    <?php if($hak_akses != 1): ?>
    <span class="badge badge-secondary ml-2">Admin Unit: <?php echo $user_unit?></span>
    <?php endif; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <!-- Statistics Cards Row -->
  <div class="row">

    <!-- Total Pegawai Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Pegawai
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $total_pegawai?> <small>Orang</small>
              </div>
              <small class="text-muted">
                <i class="fas fa-check-circle text-success"></i> Tetap: <?php echo $pegawai_tetap?>
                <i class="fas fa-clock text-warning ml-2"></i> Tidak Tetap: <?php echo $pegawai_tidak_tetap?>
              </small>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenaga Pendidik Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Tenaga Pendidik
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php 
                $pendidik = 0;
                foreach($per_kategori as $k) {
                  if($k->kategori == 'Pendidik') $pendidik = $k->jumlah;
                }
                echo $pendidik;
                ?> <small>Guru</small>
              </div>
              <small class="text-muted">Guru & Pengajar</small>
            </div>
            <div class="col-auto">
              <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenaga Kependidikan Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Tenaga Kependidikan
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php 
                $tendik = 0;
                foreach($per_kategori as $k) {
                  if($k->kategori == 'Tendik') $tendik = $k->jumlah;
                }
                echo $tendik;
                ?> <small>Orang</small>
              </div>
              <small class="text-muted">Staff & Admin</small>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tie fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Absensi Bulan Ini Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Data Absensi
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $absensi_bulan_ini?> <small>Record</small>
              </div>
              <small class="text-muted">Bulan: <?php echo date('F Y')?></small>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Charts Row -->
  <div class="row">

    <!-- Chart Jenis Kelamin -->
    <div class="col-xl-4 col-lg-5 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-pie"></i> Komposisi Jenis Kelamin
          </h6>
        </div>
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="myPieChart"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-3">
              <i class="fas fa-circle text-primary"></i> Laki-Laki (<?php echo $jk_laki?>)
            </span>
            <span>
              <i class="fas fa-circle text-success"></i> Perempuan (<?php echo $jk_perempuan?>)
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Kategori Pegawai -->
    <div class="col-xl-8 col-lg-7 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-bar"></i> Distribusi Pegawai per Kategori
          </h6>
        </div>
        <div class="card-body">
          <div class="chart-bar">
            <canvas id="myBarChart"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Top Units & Quick Actions Row (Super Admin Only) -->
  <?php if($hak_akses == 1 && $selected_unit == 'ALL' && count($top_units) > 0): ?>
  <div class="row">

    <!-- Top 5 Units -->
    <div class="col-xl-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3 bg-gradient-primary">
          <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-trophy"></i> Top 5 Unit Berdasarkan Jumlah Pegawai
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="thead-light">
                <tr>
                  <th width="10%" class="text-center">No</th>
                  <th width="20%" class="text-center">Kode</th>
                  <th>Nama Unit</th>
                  <th width="20%" class="text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1;
                foreach($top_units as $tu): 
                ?>
                <tr>
                  <td class="text-center"><?php echo $no++?></td>
                  <td class="text-center">
                    <span class="badge badge-info"><?php echo $tu->kode_unit?></span>
                  </td>
                  <td><?php echo $tu->nama_unit?></td>
                  <td class="text-center">
                    <strong><?php echo $tu->jumlah?></strong> orang
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3 bg-gradient-success">
          <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-bolt"></i> Quick Actions
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <a href="<?php echo base_url('admin/data_unit')?>" class="btn btn-primary btn-block">
                <i class="fas fa-school"></i><br>
                <small>Kelola Unit</small>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="<?php echo base_url('admin/data_pegawai')?>" class="btn btn-success btn-block">
                <i class="fas fa-users"></i><br>
                <small>Data Pegawai</small>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="<?php echo base_url('admin/data_absensi')?>" class="btn btn-info btn-block">
                <i class="fas fa-clipboard-check"></i><br>
                <small>Input Absensi</small>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a href="<?php echo base_url('admin/data_penggajian')?>" class="btn btn-warning btn-block">
                <i class="fas fa-money-check-alt"></i><br>
                <small>Data Gaji</small>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <?php endif; ?>

  <!-- Status Kepegawaian Distribution -->
  <?php if(count($per_status) > 0): ?>
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-id-badge"></i> Distribusi Status Kepegawaian
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <?php foreach($per_status as $ps): ?>
            <div class="col-md-3 mb-3">
              <div class="card bg-light">
                <div class="card-body text-center">
                  <h2 class="font-weight-bold text-primary"><?php echo $ps->jumlah?></h2>
                  <p class="mb-0">
                    <?php 
                    $badge_class = 'badge-secondary';
                    if($ps->status_kepegawaian == 'PNS') $badge_class = 'badge-primary';
                    elseif($ps->status_kepegawaian == 'GTY') $badge_class = 'badge-success';
                    elseif($ps->status_kepegawaian == 'GTT') $badge_class = 'badge-warning';
                    ?>
                    <span class="badge <?php echo $badge_class?> badge-lg">
                      <?php echo $ps->status_kepegawaian?>
                    </span>
                  </p>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
<!-- /.container-fluid -->

<!-- Custom Chart Scripts -->
<script>
// Pie Chart - Jenis Kelamin
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Laki-Laki", "Perempuan"],
    datasets: [{
      data: [<?php echo $jk_laki?>, <?php echo $jk_perempuan?>],
      backgroundColor: ['#4e73df', '#1cc88a'],
      hoverBackgroundColor: ['#2e59d9', '#17a673'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});

// Bar Chart - Kategori Pegawai
var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [
      <?php 
      foreach($per_kategori as $k) {
        echo "'".$k->kategori."',";
      }
      ?>
    ],
    datasets: [{
      label: "Jumlah Pegawai",
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
      borderColor: '#4e73df',
      data: [
        <?php 
        foreach($per_kategori as $k) {
          echo $k->jumlah.",";
        }
        ?>
      ],
    }],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          stepSize: 1
        }
      }]
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
  }
});
</script>