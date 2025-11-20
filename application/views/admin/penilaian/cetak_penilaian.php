<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title?></title>
	<style type="text/css">
		body{
			font-family: Arial;
			color: black;
		}
		table {
			border-collapse: collapse;
			width: 100%;
		}
		table, th, td {
			border: 1px solid black;
			padding: 5px;
		}
		th {
			background-color: #4e73df;
			color: white;
		}
	</style>
</head>
<body>
	<center>
		<h1>Yayasan Wakaf Cendekia Takengon</h1>
		<h2>Laporan Penilaian Kinerja Karyawan</h2>
	</center>

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
	<table style="border: none; margin-bottom: 20px;">
		<tr style="border: none;">
			<td style="border: none;" width="100">Bulan</td>
			<td style="border: none;" width="10">:</td>
			<td style="border: none;"><?php echo $bulan?></td>
		</tr>
		<tr style="border: none;">
			<td style="border: none;">Tahun</td>
			<td style="border: none;">:</td>
			<td style="border: none;"><?php echo $tahun?></td>
		</tr>
	</table>

	<table>
		<tr>
			<th>No</th>
			<th>NIK</th>
			<th>Nama Pegawai</th>
			<th>Jabatan</th>
			<th>Kedisiplinan</th>
			<th>Kerjasama</th>
			<th>Tanggung Jawab</th>
			<th>Kualitas Kerja</th>
			<th>Total Nilai</th>
			<th>Kategori</th>
		</tr>
		<?php 
		$no=1; 
		$total_keseluruhan = 0;
		$jumlah_karyawan = count($lap_penilaian);
		foreach($lap_penilaian as $lp) : 
			$total_keseluruhan += $lp->total_nilai;
		?>
		<tr>
			<td style="text-align: center;"><?php echo $no++ ?></td>
			<td style="text-align: center;"><?php echo $lp->nik ?></td>
			<td><?php echo $lp->nama_pegawai ?></td>
			<td style="text-align: center;"><?php echo $lp->jabatan ?></td>
			<td style="text-align: center;"><?php echo $lp->kedisiplinan ?></td>
			<td style="text-align: center;"><?php echo $lp->kerjasama ?></td>
			<td style="text-align: center;"><?php echo $lp->tanggung_jawab ?></td>
			<td style="text-align: center;"><?php echo $lp->kualitas_kerja ?></td>
			<td style="text-align: center;"><strong><?php echo $lp->total_nilai ?></strong></td>
			<td style="text-align: center;"><strong><?php echo $lp->kategori ?></strong></td>
		</tr>
		<?php endforeach ;?>
		<tr>
			<th colspan="8" style="text-align: right;">Rata-rata Penilaian:</th>
			<th colspan="2" style="text-align: center;">
				<?php 
				if($jumlah_karyawan > 0) {
					$rata_rata = $total_keseluruhan / $jumlah_karyawan;
					echo number_format($rata_rata, 2);
				} else {
					echo "0";
				}
				?>
			</th>
		</tr>
	</table>

	<br><br>
	<table width="100%" style="border: none;">
		<tr style="border: none;">
			<td style="border: none;"></td>
			<td style="border: none; text-align: center;" width="250px">
				<p>Takengon, <?php echo date("d M Y") ?><br>HRD Manager</p>
				<br><br><br>
				<p>_______________________</p>
			</td>
		</tr>
	</table>

</body>
</html>

<script type="text/javascript">
	window.print();
</script>