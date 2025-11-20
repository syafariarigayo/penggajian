<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>

</div>
<!-- /.container-fluid -->

<div class="card" style="width: 70% ; margin-bottom: 100px">
	<div class="card-header bg-primary text-white">
		<strong>Form Edit Penilaian Karyawan</strong>
	</div>
	<div class="card-body">
		<?php foreach ($penilaian as $p): ?>
		<form method="POST" action="<?php echo base_url('admin/penilaian_karyawan/edit_penilaian_aksi')?>">
			
			<input type="hidden" name="id_penilaian" value="<?php echo $p->id_penilaian?>">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>NIK</label>
						<input type="text" class="form-control" value="<?php echo $p->nik?>" readonly>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Pegawai</label>
						<input type="text" class="form-control" value="<?php echo $p->nama_pegawai?>" readonly>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Jabatan</label>
						<input type="text" class="form-control" value="<?php echo $p->jabatan?>" readonly>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Bulan/Tahun</label>
						<input type="text" class="form-control" value="<?php echo substr($p->bulan, 0,2).'/'.substr($p->bulan, 2,4)?>" readonly>
					</div>
				</div>
			</div>

			<hr>
			<h5 class="mb-3"><strong>Aspek Penilaian (Skala 0-100)</strong></h5>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Kedisiplinan <span class="text-danger">*</span></label>
						<input type="number" name="kedisiplinan" class="form-control" min="0" max="100" value="<?php echo $p->kedisiplinan?>" required>
						<small class="form-text text-muted">Kehadiran, ketepatan waktu</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kerjasama <span class="text-danger">*</span></label>
						<input type="number" name="kerjasama" class="form-control" min="0" max="100" value="<?php echo $p->kerjasama?>" required>
						<small class="form-text text-muted">Teamwork, komunikasi</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Tanggung Jawab <span class="text-danger">*</span></label>
						<input type="number" name="tanggung_jawab" class="form-control" min="0" max="100" value="<?php echo $p->tanggung_jawab?>" required>
						<small class="form-text text-muted">Penyelesaian tugas, inisiatif</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Kualitas Kerja <span class="text-danger">*</span></label>
						<input type="number" name="kualitas_kerja" class="form-control" min="0" max="100" value="<?php echo $p->kualitas_kerja?>" required>
						<small class="form-text text-muted">Hasil kerja, ketelitian</small>
					</div>
				</div>
			</div>

			<hr>
			<div class="alert alert-info">
				<strong>Informasi Kategori:</strong><br>
				• 340-400: Sangat Baik<br>
				• 260-339: Baik<br>
				• 180-259: Cukup<br>
				• 0-179: Kurang
			</div>

			<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Penilaian</button>
			<a href="<?php echo base_url('admin/penilaian_karyawan')?>" class="btn btn-warning"><i class="fas fa-arrow-left"></i> Kembali</a>

		</form>
	<?php endforeach; ?>
	</div>
</div>