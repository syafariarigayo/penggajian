<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-school"></i> <?php echo $title?></h1>
  </div>

  <!-- Unit Info Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-info-circle"></i> Informasi Unit Pendidikan
      </h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="180"><strong>Kode Unit</strong></td>
              <td width="20">:</td>
              <td>
                <span class="badge badge-info" style="font-size: 14px;">
                  <?php echo $unit->kode_unit?>
                </span>
              </td>
            </tr>
            <tr>
              <td><strong>Nama Unit</strong></td>
              <td>:</td>
              <td><?php echo $unit->nama_unit?></td>
            </tr>
            <tr>
              <td><strong>Jenjang</strong></td>
              <td>:</td>
              <td>
                <?php 
                $badge_color = '';
                switch($unit->jenjang) {
                  case 'Yayasan': $badge_color = 'badge-dark'; break;
                  case 'SMA': $badge_color = 'badge-primary'; break;
                  case 'SMP': $badge_color = 'badge-success'; break;
                  case 'SD': $badge_color = 'badge-warning'; break;
                  case 'TK': $badge_color = 'badge-info'; break;
                  case 'TPA': $badge_color = 'badge-secondary'; break;
                  case 'KB': $badge_color = 'badge-light'; break;
                  default: $badge_color = 'badge-secondary';
                }
                ?>
                <span class="badge <?php echo $badge_color?>">
                  <?php echo $unit->jenjang?>
                </span>
              </td>
            </tr>
            <tr>
              <td><strong>Kepala Unit</strong></td>
              <td>:</td>
              <td><?php echo $unit->kepala_unit ? $unit->kepala_unit : '<em class="text-muted">Belum ditentukan</em>'?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Alamat</strong></td>
              <td width="20">:</td>
              <td><?php echo $unit->alamat ? $unit->alamat : '<em class="text-muted">-</em>'?></td>
            </tr>
            <tr>
              <td><strong>Telepon</strong></td>
              <td>:</td>
              <td>
                <?php if($unit->telepon) { ?>
                  <i class="fas fa-phone text-primary"></i> <?php echo $unit->telepon?>
                <?php } else { ?>
                  <em class="text-muted">-</em>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><strong>Status</strong></td>
              <td>:</td>
              <td>
                <?php if($unit->status == 'Aktif') { ?>
                  <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> Aktif
                  </span>
                <?php } else { ?>
                  <span class="badge badge-danger">
                    <i class="fas fa-times-circle"></i> Nonaktif
                  </span>
                <?php } ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
      
      <hr>
      
      <div class="text-right">
        <a href="<?php echo base_url('admin/data_unit/update_unit/'.$unit->id_unit)?>" class="btn btn-sm btn-info">
          <i class="fas fa-edit"></i> Edit Unit
        </a>
        <a href="<?php echo base_url('admin/data_unit')?>" class="btn btn-sm btn-warning">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <!-- Statistics Cards Row -->
  <div class="row">
    
    <!-- Total Pegawai -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Pegawai
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $stats['total_pegawai']?> Orang
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenaga Pendidik -->
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
                foreach($stats['per_kategori'] as $k) {
                  if($k->kategori == 'Pendidik') $pendidik = $k->jumlah;
                }
                echo $pendidik;
                ?> Guru
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tenaga Kependidikan -->
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
                foreach($stats['per_kategori'] as $k) {
                  if($k->kategori == 'Tendik') $tendik = $k->jumlah;
                }
                echo $tendik;
                ?> Orang
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tie fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Manajemen -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Manajemen
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php 
                $manajemen = 0;
                foreach($stats['per_kategori'] as $k) {
                  if($k->kategori == 'Manajemen') $manajemen = $k->jumlah;
                }
                echo $manajemen;
                ?> Orang
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-cog fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Daftar Pegawai di Unit Ini -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-success">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-users"></i> Daftar Pegawai di Unit Ini
      </h6>
    </div>
    <div class="card-body">
      <?php if(count($pegawai) > 0) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead class="thead-dark">
            <tr>
              <th class="text-center" width="5%">No</th>
              <th class="text-center">NIK</th>
              <th class="text-center">Nama Pegawai</th>
              <th class="text-center">Jabatan</th>
              <th class="text-center">Kategori</th>
              <th class="text-center">Status Kepegawaian</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1; 
            foreach($pegawai as $p) : 
              // Get kategori dari jabatan
              $kategori = $this->db->select('kategori')
                               ->from('data_jabatan')
                               ->where('nama_jabatan', $p->jabatan)
                               ->get()
                               ->row();
            ?>
            <tr>
              <td class="text-center"><?php echo $no++?></td>
              <td class="text-center"><?php echo $p->nik?></td>
              <td><?php echo $p->nama_pegawai?></td>
              <td><?php echo $p->jabatan?></td>
              <td class="text-center">
                <?php if($kategori) { ?>
                  <?php if($kategori->kategori == 'Pendidik') { ?>
                    <span class="badge badge-success"><?php echo $kategori->kategori?></span>
                  <?php } elseif($kategori->kategori == 'Tendik') { ?>
                    <span class="badge badge-info"><?php echo $kategori->kategori?></span>
                  <?php } else { ?>
                    <span class="badge badge-warning"><?php echo $kategori->kategori?></span>
                  <?php } ?>
                <?php } ?>
              </td>
              <td class="text-center">
                <?php 
                $status_badge = 'badge-secondary';
                if($p->status_kepegawaian == 'PNS') $status_badge = 'badge-primary';
                elseif($p->status_kepegawaian == 'GTY') $status_badge = 'badge-success';
                elseif($p->status_kepegawaian == 'GTT') $status_badge = 'badge-warning';
                ?>
                <span class="badge <?php echo $status_badge?>">
                  <?php echo $p->status_kepegawaian?>
                </span>
              </td>
              <td class="text-center">
                <span class="badge badge-<?php echo ($p->status == 'Karyawan Tetap') ? 'success' : 'warning'?>">
                  <?php echo $p->status?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php } else { ?>
        <div class="alert alert-info">
          <i class="fas fa-info-circle"></i> Belum ada pegawai yang terdaftar di unit ini.
        </div>
      <?php } ?>
    </div>
  </div>

</div>
<!-- /.container-fluid -->