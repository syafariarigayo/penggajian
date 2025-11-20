<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-edit"></i> <?php echo $title?></h1>
  </div>

</div>
<!-- /.container-fluid -->

<div class="card mx-auto" style="width: 70%; margin-bottom: 100px">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0"><i class="fas fa-school"></i> Form Update Unit Pendidikan</h5>
  </div>
  <div class="card-body">
    <?php foreach ($unit as $u): ?>
    <form method="POST" action="<?php echo base_url('admin/data_unit/update_unit_aksi')?>">
      
      <input type="hidden" name="id_unit" value="<?php echo $u->id_unit?>">

      <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>Perhatian:</strong> Perubahan kode unit akan mempengaruhi semua data pegawai yang terdaftar di unit ini.
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kode Unit <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="kode_unit" class="form-control" value="<?php echo $u->kode_unit?>" maxlength="10" style="text-transform: uppercase;" <?php echo ($u->kode_unit == 'YYS') ? 'readonly' : ''; ?>>
          <?php echo form_error('kode_unit', '<small class="text-danger">', '</small>')?>
          <?php if($u->kode_unit == 'YYS') { ?>
          <small class="form-text text-muted">
            <i class="fas fa-lock"></i> Kode Yayasan tidak bisa diubah
          </small>
          <?php } else { ?>
          <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> Maksimal 10 karakter, huruf kapital, tanpa spasi
          </small>
          <?php } ?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama Unit <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="nama_unit" class="form-control" value="<?php echo $u->nama_unit?>">
          <?php echo form_error('nama_unit', '<small class="text-danger">', '</small>')?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <select name="jenjang" class="form-control">
            <option value="">-- Pilih Jenjang --</option>
            <option value="Yayasan" <?php echo ($u->jenjang == 'Yayasan') ? 'selected' : ''; ?>>Yayasan</option>
            <option value="SMA" <?php echo ($u->jenjang == 'SMA') ? 'selected' : ''; ?>>SMA (Sekolah Menengah Atas)</option>
            <option value="SMP" <?php echo ($u->jenjang == 'SMP') ? 'selected' : ''; ?>>SMP (Sekolah Menengah Pertama)</option>
            <option value="SD" <?php echo ($u->jenjang == 'SD') ? 'selected' : ''; ?>>SD (Sekolah Dasar)</option>
            <option value="TK" <?php echo ($u->jenjang == 'TK') ? 'selected' : ''; ?>>TK (Taman Kanak-Kanak)</option>
            <option value="TPA" <?php echo ($u->jenjang == 'TPA') ? 'selected' : ''; ?>>TPA (Taman Pendidikan Al-Quran)</option>
            <option value="KB" <?php echo ($u->jenjang == 'KB') ? 'selected' : ''; ?>>KB (Kelompok Bermain)</option>
          </select>
          <?php echo form_error('jenjang', '<small class="text-danger">', '</small>')?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kepala Unit</label>
        <div class="col-sm-9">
          <input type="text" name="kepala_unit" class="form-control" value="<?php echo $u->kepala_unit?>">
          <small class="form-text text-muted">
            <i class="fas fa-user-tie"></i> Nama Kepala Sekolah/Koordinator
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
          <textarea name="alamat" class="form-control" rows="3"><?php echo $u->alamat?></textarea>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Telepon</label>
        <div class="col-sm-9">
          <input type="text" name="telepon" class="form-control" value="<?php echo $u->telepon?>">
          <small class="form-text text-muted">
            <i class="fas fa-phone"></i> Nomor telepon/HP unit
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <select name="status" class="form-control">
            <option value="Aktif" <?php echo ($u->status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="Nonaktif" <?php echo ($u->status == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
          </select>
          <?php echo form_error('status', '<small class="text-danger">', '</small>')?>
          <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> Unit nonaktif tidak akan muncul di filter dan laporan
          </small>
        </div>
      </div>

      <hr>

      <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Update Unit
          </button>
          <a href="<?php echo base_url('admin/data_unit')?>" class="btn btn-warning">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>

    </form>
    <?php endforeach; ?>
  </div>
</div>