<!-- application/views/admin/fingerprint/kelola_device.php -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-server"></i> <?php echo $title?>
    </h1>
    <a href="<?php echo base_url('admin/absensi_fingerprint')?>" class="btn btn-sm btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <?php echo $this->session->flashdata('pesan')?>

  <div class="row">

    <!-- Form Tambah Device -->
    <div class="col-md-4 mb-4">
      <div class="card shadow">
        <div class="card-header bg-success text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="fas fa-plus-circle"></i> Tambah Mesin Baru
          </h6>
        </div>
        <div class="card-body">
          <form method="POST" action="<?php echo base_url('admin/absensi_fingerprint/tambah_device_aksi')?>">

            <div class="form-group">
              <label>Serial Number / Device ID <span class="text-danger">*</span></label>
              <input type="text" name="device_id" class="form-control"
                placeholder="Contoh: ABC1234567" required>
              <small class="text-muted">
                Cek di menu mesin: Menu → Info → Serial Number
              </small>
            </div>

            <div class="form-group">
              <label>Nama Mesin <span class="text-danger">*</span></label>
              <input type="text" name="device_name" class="form-control"
                placeholder="Contoh: Fingerprint SMA" required>
            </div>

            <div class="form-group">
              <label>Unit <span class="text-danger">*</span></label>
              <select name="unit" class="form-control" required>
                <option value="">-- Pilih Unit --</option>
                <?php
                $units = $this->db->where('status','Aktif')->get('data_unit')->result();
                foreach($units as $u): ?>
                <option value="<?php echo $u->kode_unit?>">
                  <?php echo $u->kode_unit?> - <?php echo $u->nama_unit?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>IP Address</label>
              <input type="text" name="ip_address" class="form-control"
                placeholder="Contoh: 192.168.1.100">
              <small class="text-muted">
                Cek di mesin: Menu → Communication → IP Address
              </small>
            </div>

            <div class="form-group">
              <label>Lokasi Mesin</label>
              <input type="text" name="lokasi" class="form-control"
                placeholder="Contoh: Pintu Masuk SMA">
            </div>

            <button type="submit" class="btn btn-success btn-block">
              <i class="fas fa-save"></i> Simpan Device
            </button>
          </form>
        </div>
      </div>

      <!-- Info Push URL -->
      <div class="card shadow mt-3">
        <div class="card-header bg-info text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="fas fa-info-circle"></i> Setting di Mesin ZKTeco
          </h6>
        </div>
        <div class="card-body">
          <p class="mb-2"><strong>Push URL yang digunakan:</strong></p>
          <div class="bg-dark text-white p-2 rounded" style="font-size: 12px; word-break: break-all;">
            <?php echo base_url('api/zkteco')?>
          </div>
          <hr>
          <p class="mb-1"><strong>Langkah setting di mesin:</strong></p>
          <ol class="pl-3" style="font-size: 13px;">
            <li>Menu → Communication</li>
            <li>Push Attendance Setting</li>
            <li>Enable Push: <strong>ON</strong></li>
            <li>Server Address: <strong><?php echo $_SERVER['HTTP_HOST']?></strong></li>
            <li>Server Port: <strong>80</strong></li>
            <li>Push URL: <strong>/penggajian/api/zkteco</strong></li>
          </ol>
        </div>
      </div>
    </div>

    <!-- Daftar Device -->
    <div class="col-md-8 mb-4">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list"></i> Daftar Mesin Terdaftar
          </h6>
        </div>
        <div class="card-body">
          <?php if(count($devices) > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>No</th>
                  <th>Device ID</th>
                  <th>Nama Mesin</th>
                  <th>Unit</th>
                  <th>IP Address</th>
                  <th>Lokasi</th>
                  <th>Status</th>
                  <th>Last Sync</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach($devices as $d): ?>
                <tr>
                  <td class="text-center"><?php echo $no++?></td>
                  <td><code><?php echo $d->device_id?></code></td>
                  <td><?php echo $d->device_name?></td>
                  <td><span class="badge badge-info"><?php echo $d->unit?></span></td>
                  <td><?php echo $d->ip_address ? $d->ip_address : '-'?></td>
                  <td><?php echo $d->lokasi ? $d->lokasi : '-'?></td>
                  <td class="text-center">
                    <?php if($d->status == 'aktif'): ?>
                      <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                      <span class="badge badge-danger">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if($d->last_sync): ?>
                      <small><?php echo date('d/m H:i', strtotime($d->last_sync))?></small>
                    <?php else: ?>
                      <small class="text-muted">Belum sync</small>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a onclick="return confirm('Yakin hapus device ini?')"
                       href="<?php echo base_url('admin/absensi_fingerprint/delete_device/'.$d->id)?>"
                       class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php else: ?>
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Belum ada mesin fingerprint yang terdaftar.
            Tambahkan mesin menggunakan form di sebelah kiri.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>
