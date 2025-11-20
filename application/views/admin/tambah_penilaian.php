<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>

  <div class="card mb-3">
  <div class="card-header bg-primary text-white">
    Filter Form Input Penilaian
  </div>
  <div class="card-body">
    <form class="form-inline">
	  <div class="form-group mb-2">
	    <label for="staticEmail2">Bulan</label>
	    <select class="form-control ml-3" name="bulan">
		    <option value=""> Pilih Bulan </option>
		    <option value="01">Januari</option>
		    <option value="02">Februari</option>
		    <option value="03">Maret</option>
		    <option value="04">April</option>
		    <option value="05">Mei</option>
		    <option value="06">Juni</option>
		    <option value="07">Juli</option>
		    <option value="08">Agustus</option>
		    <option value="09">September</option>
		    <option value="10">Oktober</option>
		    <option value="11">November</option>
		    <option value="12">Desember</option>
	    </select>
	  </div>
	  <div class="form-group mb-2 ml-5">
	    <label for="staticEmail2">Tahun</label>
	    <select class="form-control ml-3" name="tahun">
		    <option value=""> Pilih Tahun </option>
		    <?php $tahun = date('Y');
		    for($i=2020;$i<$tahun+5;$i++) { ?>
		    <option value="<?php echo $i ?>"><?php echo $i ?></option>
		<?php }?>
		</select>
	  </div>
	  
	  <button type="submit" class="btn btn-primary mb-2 ml-auto"><i class="fas fa-eye"></i> Generate Form</button>
	</form>
  </div>
</div>
	
<?php
	if((isset($_GET['bulan']) && $_GET['bulan']!='') && (isset($_GET['tahun']) && $_GET['tahun']!='')){
		$bulan = $_GET['bulan'];
		$tahun = $_GET['tahun'];
		$bulantahun = $bulan.$tahun;
	}else{
		$bulan = date('m');
		$tahun = date('Y');
		$bulantahun = $bulan.$tahun;
	}
?>

<div class="alert alert-info">
	<strong>Petunjuk Penilaian:</strong><br>
	Berikan nilai untuk setiap aspek dengan skala 0-100<br>
	• Kedisiplinan (Kehadiran, ketepatan waktu)<br>
	• Kerjasama (Teamwork, komunikasi)<br>
	• Tanggung Jawab (Penyelesaian tugas, inisiatif)<br>
	• Kualitas Kerja (Hasil kerja, ketelitian)<br><br>
	<strong>Kategori Penilaian:</strong><br>
	• 340-400: Sangat Baik<br>
	• 260-339: Baik<br>
	• 180-259: Cukup<br>
	• 0-179: Kurang
</div>

<div class="alert alert-warning">
	Input Penilaian Karyawan Bulan: <span class="font-weight-bold"><?php echo $bulan ?></span> Tahun: <span class="font-weight-bold"><?php echo $tahun ?></span>
</div>

<form method="POST">
<button class="btn btn-success mb-3" type="submit" name="submit" value="submit"><i class="fas fa-save"></i> Simpan Penilaian</button>
<a href="<?php echo base_url('admin/penilaian_karyawan')?>" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="table-responsive">
<table class="table table-bordered table-striped">
	<thead class="thead-dark">
		<tr>
			<td class="text-center" rowspan="2">No</td>
			<td class="text-center" rowspan="2">NIK</td>
			<td class="text-center" rowspan="2">Nama Pegawai</td>
			<td class="text-center" rowspan="2">Jabatan</td>
			<td class="text-center" colspan="4">Aspek Penilaian (0-100)</td>
		</tr>
		<tr>
			<td class="text-center">Kedisiplinan</td>
			<td class="text-center">Kerjasama</td>
			<td class="text-center">Tanggung Jawab</td>
			<td class="text-center">Kualitas Kerja</td>
		</tr>
	</thead>
	<tbody>
	<?php $no=1; foreach($input_penilaian as $ip) :?>
		<tr>
			<input type="hidden" name="bulan[]" class="form-control" value="<?php echo $bulantahun?>">
			<input type="hidden" name="nik[]" class="form-control" value="<?php echo $ip->nik?>">
			<input type="hidden" name="nama_pegawai[]" class="form-control" value="<?php echo $ip->nama_pegawai?>">
			<input type="hidden" name="jabatan[]" class="form-control" value="<?php echo $ip->nama_jabatan?>">

			<td class="text-center"><?php echo $no++?></td>
			<td class="text-center"><?php echo $ip->nik?></td>
			<td><?php echo $ip->nama_pegawai?></td>
			<td class="text-center"><?php echo $ip->nama_jabatan?></td>
			<td><input type="number" name="kedisiplinan[]" class="form-control" min="0" max="100" value="0" required></td>
			<td><input type="number" name="kerjasama[]" class="form-control" min="0" max="100" value="0" required></td>
			<td><input type="number" name="tanggung_jawab[]" class="form-control" min="0" max="100" value="0" required></td>
			<td><input type="number" name="kualitas_kerja[]" class="form-control" min="0" max="100" value="0" required></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
</form>

</div>
<!-- /.container-fluid -->