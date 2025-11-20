<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-plus-circle"></i> <?php echo $title?></h1>
  </div>

</div>
<!-- /.container-fluid -->

<div class="card mx-auto" style="width: 70%; margin-bottom: 100px">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0"><i class="fas fa-school"></i> Form Tambah Unit Pendidikan</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo base_url('admin/data_unit/tambah_unit_aksi')?>">
      
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        <strong>Petunjuk:</strong> Isi semua data dengan lengkap. Kode unit harus unik dan akan otomatis diubah ke huruf kapital.
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kode Unit <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="kode_unit" class="form-control" placeholder="Contoh: SMA, SMP, SD" maxlength="10" style="text-transform: uppercase;">
          <?php echo form_error('kode_unit', '<small class="text-danger">', '</small>')?>
          <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> Maksimal 10 karakter, huruf kapital, tanpa spasi. Contoh: SMA, SMP, SD, TK
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama Unit <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <input type="text" name="nama_unit" class="form-control" placeholder="Contoh: SMA Wakaf Cendekia Takengon">
          <?php echo form_error('nama_unit', '<small class="text-danger">', '</small>')?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <select name="jenjang" class="form-control">
            <option value="">-- Pilih Jenjang --</option>
            <option value="Yayasan">Yayasan</option>
            <option value="SMA">SMA (Sekolah Menengah Atas)</option>
            <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
            <option value="SD">SD (Sekolah Dasar)</option>
            <option value="TK">TK (Taman Kanak-Kanak)</option>
            <option value="TPA">TPA (Taman Pendidikan Al-Quran)</option>
            <option value="KB">KB (Kelompok Bermain)</option>
          </select>
          <?php echo form_error('jenjang', '<small class="text-danger">', '</small>')?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kepala Unit</label>
        <div class="col-sm-9">
          <input type="text" name="kepala_unit" class="form-control" placeholder="Nama Kepala Sekolah/Koordinator">
          <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> Opsional. Bisa diisi nanti setelah penunjukan.
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
          <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap unit pendidikan"></textarea>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Telepon</label>
        <div class="col-sm-9">
          <input type="text" name="telepon" class="form-control" placeholder="Nomor telepon/HP">
          <small class="form-text text-muted">
            <i class="fas fa-phone"></i> Format: 08xx-xxxx-xxxx atau (0xxx) xxxxxx
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
        <div class="col-sm-9">
          <select name="status" class="form-control">
            <option value="">-- Pilih Status --</option>
            <option value="Aktif" selected>Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
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
            <i class="fas fa-save"></i> Simpan Unit
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
          <a href="<?php echo base_url('admin/data_unit')?>" class="btn btn-warning">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>

    </form>
  </div>
</div>