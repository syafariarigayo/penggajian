<!-- application/views/admin/fingerprint/detail_log.php -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-search"></i> <?php echo $title?>
    </h1>
    <a href="javascript:history.back()" class="btn btn-sm btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Info Pegawai -->
  <?php if($pegawai): ?>
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
      <h6 class="m-0 font-weight-bold">
        <i class="fas fa-user"></i> Informasi Pegawai
      </h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless mb-0">
            <tr>
              <td width="150"><strong>Nama</strong></td>
              <td>: <?php echo $pegawai->nama_pegawai?></td>
            </tr>
            <tr>
              <td><strong>NIK</strong></td>
              <td>: <?php echo $pegawai->nik?></td>
            </tr>
            <tr>
              <td><strong>Jabatan</strong></td>
              <td>: <?php echo $pegawai->jabatan?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless mb-0">
            <tr>
              <td width="150"><strong>Unit</strong></td>
              <td>: <span class="badge badge-info"><?php echo isset($pegawai->unit) ? $pegawai->unit : '-'?></span></td>
            </tr>
            <tr>
              <td><strong>Tanggal</strong></td>
              <td>: <strong><?php echo date('l, d F Y', strtotime($tanggal))?></strong></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="row">

    <!-- Rekap Hari Ini -->
    <div class="col-md-5 mb-4">
      <div class="card shadow h-100">
        <div class="card-header bg-success text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="fas fa-chart-bar"></i> Rekap Kehadiran
          </h6>
        </div>
        <div class="card-body">
          <?php if($rekap): ?>
          <table class="table table-borderless">
            <tr>
              <td><strong>Jam Masuk</strong></td>
              <td>:</td>
              <td>
                <?php if($rekap->jam_masuk): ?>
                  <strong class="<?php echo ($rekap->status_kehadiran == 'Terlambat') ? 'text-danger' : 'text-success'?>">
                    <?php echo date('H:i:s', strtotime($rekap->jam_masuk))?>
                  </strong>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td><strong>Jam Pulang</strong></td>
              <td>:</td>
              <td>
                <?php if($rekap->jam_pulang): ?>
                  <strong><?php echo date('H:i:s', strtotime($rekap->jam_pulang))?></strong>
                <?php else: ?>
                  <span class="badge badge-warning">Belum Pulang</span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td><strong>Jam Normal</strong></td>
              <td>:</td>
              <td><?php echo date('H:i', strtotime($rekap->jam_masuk_normal))?></td>
            </tr>
            <tr>
              <td><strong>Durasi Kerja</strong></td>
              <td>:</td>
              <td><?php echo $rekap->durasi_kerja ? $rekap->durasi_kerja : '-'?></td>
            </tr>
            <tr>
              <td><strong>Status</strong></td>
              <td>:</td>
              <td>
                <?php
                $badge = 'badge-secondary';
                if($rekap->status_kehadiran == 'Hadir')     $badge = 'badge-success';
                if($rekap->status_kehadiran == 'Terlambat') $badge = 'badge-warning';
                if($rekap->status_kehadiran == 'Alpha')     $badge = 'badge-danger';
                if($rekap->status_kehadiran == 'Sakit')     $badge = 'badge-info';
                ?>
                <span class="badge <?php echo $badge?> badge-lg" style="font-size: 14px;">
                  <?php echo $rekap->status_kehadiran?>
                </span>
              </td>
            </tr>
            <?php if($rekap->menit_terlambat > 0): ?>
            <tr>
              <td><strong>Terlambat</strong></td>
              <td>:</td>
              <td>
                <span class="text-danger font-weight-bold">
                  <?php echo $rekap->menit_terlambat?> menit
                </span>
              </td>
            </tr>
            <?php endif; ?>
            <?php if($rekap->keterangan): ?>
            <tr>
              <td><strong>Keterangan</strong></td>
              <td>:</td>
              <td><?php echo $rekap->keterangan?></td>
            </tr>
            <?php endif; ?>
          </table>
          <?php else: ?>
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Belum ada rekap untuk tanggal ini.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Log Tap Fingerprint -->
    <div class="col-md-7 mb-4">
      <div class="card shadow h-100">
        <div class="card-header bg-dark text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="fas fa-fingerprint"></i> Log Tap Fingerprint
            <span class="badge badge-light ml-2"><?php echo count($logs)?> kali tap</span>
          </h6>
        </div>
        <div class="card-body">
          <?php if(count($logs) > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="thead-dark">
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Waktu</th>
                  <th class="text-center">Tipe</th>
                  <th class="text-center">Device</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach($logs as $l): ?>
                <tr>
                  <td class="text-center"><?php echo $no++?></td>
                  <td class="text-center">
                    <strong><?php echo date('H:i:s', strtotime($l->waktu))?></strong>
                  </td>
                  <td class="text-center">
                    <?php if($l->tipe == 'masuk'): ?>
                      <span class="badge badge-success">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                      </span>
                    <?php elseif($l->tipe == 'pulang'): ?>
                      <span class="badge badge-primary">
                        <i class="fas fa-sign-out-alt"></i> Pulang
                      </span>
                    <?php else: ?>
                      <span class="badge badge-secondary">Unknown</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <small><?php echo $l->device_name ? $l->device_name : $l->device_id?></small>
                  </td>
                  <td class="text-center">
                    <?php if($l->status == 'processed'): ?>
                      <span class="badge badge-success">
                        <i class="fas fa-check"></i> Diproses
                      </span>
                    <?php else: ?>
                      <span class="badge badge-warning">Pending</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Timeline -->
          <div class="mt-3">
            <h6 class="font-weight-bold text-muted">Timeline:</h6>
            <div class="d-flex align-items-center flex-wrap">
              <?php foreach($logs as $idx => $l): ?>
              <div class="text-center mr-3 mb-2">
                <div class="<?php echo ($l->tipe == 'masuk') ? 'bg-success' : 'bg-primary'?> 
                            text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width:40px; height:40px; font-size:11px;">
                  <?php echo date('H:i', strtotime($l->waktu))?>
                </div>
                <div style="font-size:10px;" class="text-muted mt-1">
                  <?php echo ucfirst($l->tipe)?>
                </div>
              </div>
              <?php if($idx < count($logs)-1): ?>
              <i class="fas fa-arrow-right text-muted mr-3 mb-2"></i>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>

          <?php else: ?>
          <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Tidak ada log tap fingerprint untuk tanggal ini.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>
