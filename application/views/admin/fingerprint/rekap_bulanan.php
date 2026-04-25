<!-- application/views/admin/fingerprint/rekap_bulanan.php -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-calendar-alt"></i> <?php echo $title?>
    </h1>
    <a href="<?php echo base_url('admin/absensi_fingerprint')?>" class="btn btn-sm btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Filter -->
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
      <h6 class="m-0 font-weight-bold">Filter Rekap Bulanan</h6>
    </div>
    <div class="card-body">
      <form method="GET" class="form-inline">
        <div class="form-group mb-2 mr-3">
          <label class="mr-2">Bulan</label>
          <select name="bulan" class="form-control">
            <?php
            $bulan_list = [
              '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
              '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
              '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
            ];
            foreach($bulan_list as $k => $v): ?>
            <option value="<?php echo $k?>" <?php echo ($bulan == $k) ? 'selected' : ''?>>
              <?php echo $v?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group mb-2 mr-3">
          <label class="mr-2">Tahun</label>
          <select name="tahun" class="form-control">
            <?php for($i = date('Y'); $i >= 2020; $i--): ?>
            <option value="<?php echo $i?>" <?php echo ($tahun == $i) ? 'selected' : ''?>>
              <?php echo $i?>
            </option>
            <?php endfor; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary mb-2 mr-2">
          <i class="fas fa-search"></i> Tampilkan
        </button>
        <a href="<?php echo base_url('admin/absensi_fingerprint/proses_ulang/'.$bulan.'/'.$tahun)?>"
           onclick="return confirm('Proses ulang rekap bulan ini?')"
           class="btn btn-warning mb-2">
          <i class="fas fa-sync"></i> Proses Ulang
        </a>
      </form>
    </div>
  </div>

  <!-- Statistik Bulanan -->
  <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Record</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($rekap)?></div>
            </div>
            <div class="col-auto"><i class="fas fa-list fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hadir</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_hadir?></div>
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
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Terlambat</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_terlambat?></div>
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
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Alpha</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_alpha?></div>
            </div>
            <div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Rekap -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">
        Rekap Absensi Fingerprint — 
        <?php echo $bulan_list[$bulan] ?? $bulan?> <?php echo $tahun?>
      </h6>
    </div>
    <div class="card-body">
      <?php if(count($rekap) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dataTable">
          <thead class="thead-dark">
            <tr>
              <th class="text-center">No</th>
              <th>NIK</th>
              <th>Nama Pegawai</th>
              <th class="text-center">Unit</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Jam Masuk</th>
              <th class="text-center">Jam Pulang</th>
              <th class="text-center">Durasi</th>
              <th class="text-center">Status</th>
              <th class="text-center">Terlambat</th>
              <th class="text-center">Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($rekap as $r): ?>
            <tr>
              <td class="text-center"><?php echo $no++?></td>
              <td><?php echo $r->nik?></td>
              <td><?php echo $r->nama_pegawai?></td>
              <td class="text-center">
                <span class="badge badge-info"><?php echo $r->unit?></span>
              </td>
              <td class="text-center"><?php echo date('d/m/Y', strtotime($r->tanggal))?></td>
              <td class="text-center">
                <?php if($r->jam_masuk): ?>
                  <strong class="<?php echo ($r->status_kehadiran == 'Terlambat') ? 'text-danger' : 'text-success'?>">
                    <?php echo date('H:i', strtotime($r->jam_masuk))?>
                  </strong>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($r->jam_pulang): ?>
                  <?php echo date('H:i', strtotime($r->jam_pulang))?>
                <?php else: ?>
                  <span class="badge badge-warning">Belum</span>
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
                if($r->status_kehadiran == 'Izin')      $badge = 'badge-primary';
                ?>
                <span class="badge <?php echo $badge?>">
                  <?php echo $r->status_kehadiran?>
                </span>
              </td>
              <td class="text-center">
                <?php if($r->menit_terlambat > 0): ?>
                  <span class="text-danger font-weight-bold">
                    <?php echo $r->menit_terlambat?> mnt
                  </span>
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
        Belum ada data rekap fingerprint untuk bulan ini.
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
