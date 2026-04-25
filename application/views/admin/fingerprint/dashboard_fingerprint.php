<!-- application/views/admin/fingerprint/dashboard_fingerprint.php -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-fingerprint"></i> <?php echo $title?>
    </h1>
    <div>
      <span class="text-muted"><i class="far fa-calendar-alt"></i></span>
      <strong><?php echo date('l, d F Y')?></strong>
    </div>
  </div>

  <?php echo $this->session->flashdata('pesan')?>

  <!-- Statistik Hari Ini -->
  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir Hari Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['hadir']?> Orang</div>
            </div>
            <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Terlambat</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['terlambat']?> Orang</div>
            </div>
            <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Absen</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['alpha']?> Orang</div>
            </div>
            <div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Tercatat</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total']?> Record</div>
            </div>
            <div class="col-auto"><i class="fas fa-fingerprint fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Status Device -->
  <?php if(count($devices) > 0): ?>
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header py-3 bg-dark">
          <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-server"></i> Status Mesin Fingerprint
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <?php foreach($devices as $d): ?>
            <div class="col-md-4 mb-3">
              <div class="card border">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <strong><?php echo $d->device_name?></strong><br>
                      <small class="text-muted">
                        <i class="fas fa-map-marker-alt"></i> <?php echo $d->lokasi?>
                      </small><br>
                      <small class="text-muted">
                        <i class="fas fa-network-wired"></i> <?php echo $d->ip_address?>
                      </small>
                    </div>
                    <div class="text-right">
                      <?php if($d->status == 'aktif'): ?>
                        <span class="badge badge-success">
                          <i class="fas fa-circle"></i> Aktif
                        </span>
                      <?php else: ?>
                        <span class="badge badge-danger">
                          <i class="fas fa-circle"></i> Nonaktif
                        </span>
                      <?php endif; ?>
                      <br>
                      <small class="text-muted">
                        Sync: <?php echo $d->last_sync ? date('H:i', strtotime($d->last_sync)) : 'Belum'?>
                      </small>
                    </div>
                  </div>
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

  <!-- Tabel Absensi Hari Ini -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-list"></i> Absensi Hari Ini — <?php echo date('d F Y')?>
      </h6>
      <div>
        <a href="<?php echo base_url('admin/absensi_fingerprint/rekap_bulanan')?>" 
           class="btn btn-sm btn-primary">
          <i class="fas fa-calendar-alt"></i> Rekap Bulanan
        </a>
        <a href="<?php echo base_url('admin/absensi_fingerprint/setting_jam_kerja')?>" 
           class="btn btn-sm btn-secondary">
          <i class="fas fa-cog"></i> Setting Jam
        </a>
      </div>
    </div>
    <div class="card-body">
      <?php if(count($rekap_hari_ini) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dataTable">
          <thead class="thead-dark">
            <tr>
              <th>No</th>
              <th>NIK</th>
              <th>Nama Pegawai</th>
              <th>Unit</th>
              <th>Jam Masuk</th>
              <th>Jam Pulang</th>
              <th>Durasi</th>
              <th>Status</th>
              <th>Terlambat</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($rekap_hari_ini as $r): ?>
            <tr>
              <td class="text-center"><?php echo $no++?></td>
              <td><?php echo $r->nik?></td>
              <td><?php echo $r->nama_pegawai?></td>
              <td><span class="badge badge-info"><?php echo $r->unit?></span></td>
              <td class="text-center">
                <?php if($r->jam_masuk): ?>
                  <strong><?php echo date('H:i', strtotime($r->jam_masuk))?></strong>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($r->jam_pulang): ?>
                  <?php echo date('H:i', strtotime($r->jam_pulang))?>
                <?php else: ?>
                  <span class="badge badge-warning">Belum Pulang</span>
                <?php endif; ?>
              </td>
              <td class="text-center"><?php echo $r->durasi_kerja ? $r->durasi_kerja : '-'?></td>
              <td class="text-center">
                <?php
                $badge = 'badge-secondary';
                if($r->status_kehadiran == 'Hadir')     $badge = 'badge-success';
                if($r->status_kehadiran == 'Terlambat') $badge = 'badge-warning';
                if($r->status_kehadiran == 'Alpha')     $badge = 'badge-danger';
                if($r->status_kehadiran == 'Sakit')     $badge = 'badge-info';
                ?>
                <span class="badge <?php echo $badge?>">
                  <?php echo $r->status_kehadiran?>
                </span>
              </td>
              <td class="text-center">
                <?php if($r->menit_terlambat > 0): ?>
                  <span class="text-danger"><?php echo $r->menit_terlambat?> menit</span>
                <?php else: ?>
                  <span class="text-success">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?php echo base_url('admin/absensi_fingerprint/detail_log/'.$r->nik.'/'.$r->tanggal)?>"
                   class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Belum ada data absensi fingerprint hari ini.
        <?php if(count($devices) == 0): ?>
        <br><strong>Tip:</strong> Tambahkan mesin fingerprint terlebih dahulu di menu 
        <a href="<?php echo base_url('admin/absensi_fingerprint/kelola_device')?>">Kelola Device</a>.
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- Auto refresh setiap 60 detik -->
<script>
setTimeout(function() {
    location.reload();
}, 60000);

// Countdown timer
var countdown = 60;
setInterval(function() {
    countdown--;
    if(document.getElementById('countdown')) {
        document.getElementById('countdown').innerText = countdown;
    }
    if(countdown <= 0) countdown = 60;
}, 1000);
</script>
