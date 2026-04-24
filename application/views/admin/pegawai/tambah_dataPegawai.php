<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>
</div>
<!-- /.container-fluid -->

<div class="card" style="width: 65%; margin-bottom: 100px">
	<div class="card-header bg-primary text-white">
		<strong><i class="fas fa-user-plus"></i> Form Tambah Data Pegawai</strong>
	</div>
	<div class="card-body">
		<form method="POST" action="<?php echo base_url('admin/data_pegawai/tambah_data_aksi')?>" enctype="multipart/form-data">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>NIK <span class="text-danger">*</span></label>
						<input type="number" name="nik" class="form-control" placeholder="Nomor Induk Karyawan">
						<?php echo form_error('nik', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Pegawai <span class="text-danger">*</span></label>
						<input type="text" name="nama_pegawai" class="form-control" placeholder="Nama lengkap">
						<?php echo form_error('nama_pegawai', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Username <span class="text-danger">*</span></label>
						<input type="text" name="username" class="form-control" placeholder="Username untuk login">
						<?php echo form_error('username', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Password <span class="text-danger">*</span></label>
						<input type="password" name="password" class="form-control" placeholder="Password untuk login">
						<?php echo form_error('password', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Jenis Kelamin <span class="text-danger">*</span></label>
						<select name="jenis_kelamin" class="form-control">
							<option value="">--Pilih Jenis Kelamin--</option>
							<option value="Laki-Laki">Laki-Laki</option>
							<option value="Perempuan">Perempuan</option>
						</select>
						<?php echo form_error('jenis_kelamin', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Jabatan <span class="text-danger">*</span></label>
						<select name="jabatan" class="form-control">
							<option value="">--Pilih Jabatan--</option>
							<?php foreach($jabatan as $j): ?>
							<option value="<?php echo $j->nama_jabatan ?>"><?php echo $j->nama_jabatan ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('jabatan', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
			</div>

			<!-- FIELD UNIT -->
			<div class="form-group">
				<label>Unit Pendidikan <span class="text-danger">*</span></label>
				<?php if($my_unit): ?>
					<!-- Kepala Unit: field dikunci ke unit sendiri -->
					<?php
					$unit_info = isset($units[0]) ? $units[0] : null;
					$nama_unit_tampil = $unit_info ? $unit_info->nama_unit : $my_unit;
					?>
					<input type="hidden" name="unit" value="<?php echo $my_unit?>">
					<input type="text" class="form-control bg-light" value="<?php echo $nama_unit_tampil?> (<?php echo $my_unit?>)" readonly>
					<small class="form-text text-muted">
						<i class="fas fa-lock"></i> Unit dikunci sesuai unit Anda.
					</small>
				<?php else: ?>
					<!-- Super Admin: pilih unit bebas -->
					<select name="unit" class="form-control">
						<option value="">--Pilih Unit Pendidikan--</option>
						<?php foreach($units as $u): ?>
						<option value="<?php echo $u->kode_unit ?>">
							<?php echo $u->kode_unit ?> - <?php echo $u->nama_unit ?>
						</option>
						<?php endforeach; ?>
					</select>
					<small class="form-text text-muted">
						<i class="fas fa-info-circle"></i> Pilih unit tempat pegawai bertugas.
					</small>
				<?php endif; ?>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Tanggal Masuk <span class="text-danger">*</span></label>
						<input type="date" name="tanggal_masuk" class="form-control">
						<?php echo form_error('tanggal_masuk', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Status <span class="text-danger">*</span></label>
						<select name="status" class="form-control">
							<option value="">--Pilih Status--</option>
							<option value="Karyawan Tetap">Karyawan Tetap</option>
							<option value="Karyawan Tidak Tetap">Karyawan Tidak Tetap</option>
						</select>
						<?php echo form_error('status', '<div class="text-small text-danger"></div>')?>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label>Hak Akses</label>
				<select name="hak_akses" class="form-control">
					<option value="">--Pilih Hak Akses--</option>
					<?php if($hak_akses == 1): ?>
					<!-- Super Admin bisa assign semua level -->
					<option value="1">Super Admin (Yayasan)</option>
					<option value="3">Kepala Unit / Admin Unit</option>
					<?php endif; ?>
					<option value="2">Pegawai</option>
				</select>
				<small class="form-text text-muted">
					<i class="fas fa-info-circle"></i>
					Pegawai = hanya bisa lihat data gaji sendiri.
					<?php if($hak_akses == 1): ?>
					Kepala Unit = bisa kelola SDM unitnya.
					<?php endif; ?>
				</small>
			</div>

			<div class="form-group">
				<label>Photo</label>
				<input type="file" name="photo" class="form-control-file">
				<small class="form-text text-muted">Format: jpg, jpeg, png, tiff. Maks 2MB.</small>
			</div>

			<hr>
			<button type="submit" class="btn btn-success">
				<i class="fas fa-save"></i> Simpan
			</button>
			<button type="reset" class="btn btn-secondary">
				<i class="fas fa-undo"></i> Reset
			</button>
			<a href="<?php echo base_url('admin/data_pegawai')?>" class="btn btn-warning">
				<i class="fas fa-arrow-left"></i> Kembali
			</a>

		</form>
	</div>
</div>