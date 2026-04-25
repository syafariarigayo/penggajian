<!-- application/views/admin/fingerprint/setting_jam_kerja.php -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-cog"></i> <?php echo $title?>
    </h1>
    <a href="<?php echo base_url('admin/absensi_fingerprint')?>" class="btn btn-sm btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <?php echo $this->session->flashdata('pesan')?>

  <div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Keterangan:</strong> Setting jam kerja digunakan untuk menentukan status kehadiran 
    (Hadir/Terlambat) dari data fingerprint. Unit <strong>ALL</strong> berlaku untuk semua unit 
    yang tidak memiliki setting khusus.
  </div>

  <!-- Tabel Setting -->
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
      <h6 class="m-0 font-weight-bold">
        <i class="fas fa-clock"></i> Jam Kerja Per Hari
      </h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th class="text-center">Unit</th>
              <th class="text-center">Hari</th>
              <th class="text-center">Jam Masuk</th>
              <th class="text-center">Jam Pulang</th>
              <th class="text-center">Toleransi</th>
              <th class="text-center">Hari Libur</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($settings as $s): ?>
            <tr id="row-<?php echo $s->id?>">
              <td class="text-center">
                <span class="badge badge-<?php echo ($s->unit == 'ALL') ? 'dark' : 'info'?>">
                  <?php echo $s->unit?>
                </span>
              </td>
              <td class="text-center font-weight-bold"><?php echo $s->hari?></td>
              <td class="text-center">
                <?php if($s->is_libur): ?>
                  <span class="text-muted">-</span>
                <?php else: ?>
                  <span class="text-success font-weight-bold">
                    <?php echo date('H:i', strtotime($s->jam_masuk))?>
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($s->is_libur): ?>
                  <span class="text-muted">-</span>
                <?php else: ?>
                  <span class="text-primary font-weight-bold">
                    <?php echo date('H:i', strtotime($s->jam_pulang))?>
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($s->is_libur): ?>
                  <span class="text-muted">-</span>
                <?php else: ?>
                  <?php echo $s->toleransi_menit?> menit
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($s->is_libur): ?>
                  <span class="badge badge-danger">Libur</span>
                <?php else: ?>
                  <span class="badge badge-success">Kerja</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-info"
                  onclick="editSetting(<?php echo $s->id?>, 
                    '<?php echo $s->jam_masuk?>', 
                    '<?php echo $s->jam_pulang?>', 
                    <?php echo $s->toleransi_menit?>,
                    <?php echo $s->is_libur?>,
                    '<?php echo $s->hari?>')">
                  <i class="fas fa-edit"></i> Edit
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">
          <i class="fas fa-edit"></i> Edit Jam Kerja — 
          <span id="modal_hari"></span>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form method="POST" action="<?php echo base_url('admin/absensi_fingerprint/update_jam_kerja_aksi')?>">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">

          <div class="form-group">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" id="edit_jam_masuk" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Jam Pulang</label>
            <input type="time" name="jam_pulang" id="edit_jam_pulang" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Toleransi Keterlambatan (menit)</label>
            <input type="number" name="toleransi_menit" id="edit_toleransi"
              class="form-control" min="0" max="120">
            <small class="text-muted">
              Pegawai yang terlambat lebih dari ini akan dihitung Terlambat
            </small>
          </div>

          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input"
                id="edit_is_libur" name="is_libur" value="1">
              <label class="custom-control-label" for="edit_is_libur">
                Tandai sebagai hari libur
              </label>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editSetting(id, jam_masuk, jam_pulang, toleransi, is_libur, hari) {
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_jam_masuk').value = jam_masuk.substring(0, 5);
  document.getElementById('edit_jam_pulang').value = jam_pulang.substring(0, 5);
  document.getElementById('edit_toleransi').value = toleransi;
  document.getElementById('edit_is_libur').checked = (is_libur == 1);
  document.getElementById('modal_hari').innerText = hari;
  $('#editModal').modal('show');
}
</script>
