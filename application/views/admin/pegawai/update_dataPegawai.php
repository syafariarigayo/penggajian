<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>
</div>
<!-- /.container-fluid -->

<div class="card" style="width: 65%; margin-bottom: 100px">
	<div class="card-header bg-primary text-white">
		<strong><i class="fas fa-user-edit"></i> Form Update Data Pegawai</strong>
	</div>
	<div class="card-body">

	<?php foreach ($pegawai as $p): ?>
	<form method="POST" action="<?php echo base_url('admin/data_pegawai/update_data_aksi')?>" enctype="multipart/form-data">

		<input type="hidden" name="id_pegawai" value="<?php echo $p->id_pegawai?>">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>NIK <span class="text-danger">*</span></label>
					<input type="number" name="nik" class="form-control" value="<?php echo $p->nik?>">
					<?php echo form_error('nik', '<div class="text-small text-danger"></div>')?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Nama Pegawai <span class="text-danger">*</span></label>
					<input type="text" name="nama_pegawai" class="form-control" value="<?php echo $p->nama_pegawai?>">
					<?php echo form_error('nama_pegawai', '<div class="text-small text-danger"></div>')?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Username</label>
					<input type="text" name="username" class="form-control" value="<?php echo $p->username?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
					<small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Jenis Kelamin <span class="text-danger">*</span></label>
					<select name="jenis_kelamin" class="form-control">
						<option value="<?php echo $p->jenis_kelamin?>"><?php echo $p->jenis_kelamin?></option>
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
						<option value="<?php echo $p->jabatan?>"><?php echo $p->jabatan?></option>
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
				<!-- Kepala Unit: dikunci -->
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
				<!-- Super Admin: bebas pilih -->
				<select name="unit" class="form-control">
					<option value="">--Pilih Unit--</option>
					<?php foreach($units as $u): ?>
					<option value="<?php echo $u->kode_unit ?>"
						<?php echo (isset($p->unit) && $p->unit == $u->kode_unit) ? 'selected' : '' ?>>
						<?php echo $u->kode_unit ?> - <?php echo $u->nama_unit ?>
					</option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Tanggal Masuk <span class="text-danger">*</span></label>
					<input type="date" name="tanggal_masuk" class="form-control" value="<?php echo $p->tanggal_masuk?>">
					<?php echo form_error('tanggal_masuk', '<div class="text-small text-danger"></div>')?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Status <span class="text-danger">*</span></label>
					<select name="status" class="form-control">
						<option value="<?php echo $p->status?>"><?php echo $p->status?></option>
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
				<option value="<?php echo $p->hak_akses?>">
					<?php
					switch($p->hak_akses) {
						case 1: echo 'Super Admin (Yayasan)'; break;
						case 3: echo 'Kepala Unit / Admin Unit'; break;
						default: echo 'Pegawai'; break;
					}
					?>
				</option>
				<?php if($hak_akses == 1): ?>
				<option value="1">Super Admin (Yayasan)</option>
				<option value="3">Kepala Unit / Admin Unit</option>
				<?php endif; ?>
				<option value="2">Pegawai</option>
			</select>
		</div>

		<div class="form-group">
			<label>Photo</label>
			<?php if($p->photo): ?>
			<div class="mb-2">
				<img src="<?php echo base_url('photo/'.$p->photo)?>" width="80" class="rounded border">
				<small class="text-muted ml-2">Photo saat ini</small>
			</div>
			<?php endif; ?>
			<input type="file" name="photo" class="form-control-file">
			<small class="form-text text-muted">Kosongkan jika tidak ingin mengubah photo.</small>
		</div>

		<hr>
		<button type="submit" class="btn btn-success">
			<i class="fas fa-save"></i> Simpan Perubahan
		</button>
		<a href="<?php echo base_url('admin/data_pegawai')?>" class="btn btn-warning">
			<i class="fas fa-arrow-left"></i> Kembali
		</a>

	</form>
	<?php endforeach; ?>
	</div>
</div>