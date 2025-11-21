<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->session->userdata('hak_akses') != '1' && 
		   $this->session->userdata('hak_akses') < 2){
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Anda Belum Login!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('login');
		}
	}

	public function index() 
	{
		$data['title'] = "Dashboard";
		$id_pegawai = $this->session->userdata('id_pegawai');
		$hak_akses = $this->session->userdata('hak_akses');
		
		// Get user data untuk mengetahui unit
		$pegawai = $this->db->get_where('data_pegawai', array('id_pegawai' => $id_pegawai))->row();
		
		// Tentukan unit yang akan ditampilkan
		if($hak_akses == 1) {
			// Super Admin - bisa pilih unit
			$selected_unit = $this->input->get('unit') ? $this->input->get('unit') : 'ALL';
		} else {
			// Admin Unit - hanya bisa lihat unit sendiri
			$selected_unit = $pegawai->unit;
		}
		
		$data['selected_unit'] = $selected_unit;
		$data['user_unit'] = $pegawai->unit;
		$data['hak_akses'] = $hak_akses;
		
		// Get list unit untuk dropdown (Super Admin only)
		if($hak_akses == 1) {
			$data['units'] = $this->ModelYayasan->get_active_units();
		} else {
			$data['units'] = array($this->ModelYayasan->get_unit_by_code($pegawai->unit));
		}
		
		// Get statistik berdasarkan unit
		if($selected_unit == 'ALL') {
			// Statistik seluruh yayasan
			$data['total_unit'] = $this->db->where('kode_unit !=', 'YYS')
									->where('status', 'Aktif')
									->count_all_results('data_unit');
			
			$data['total_pegawai'] = $this->db->count_all('data_pegawai');
			
			$data['pegawai_tetap'] = $this->db->where('status', 'Karyawan Tetap')
										->count_all_results('data_pegawai');
			
			$data['pegawai_tidak_tetap'] = $this->db->where('status', 'Karyawan Tidak Tetap')
											->count_all_results('data_pegawai');
			
			// Statistik per kategori
			$query = "SELECT j.kategori, COUNT(*) as jumlah 
					  FROM data_pegawai p 
					  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
					  GROUP BY j.kategori";
			$data['per_kategori'] = $this->db->query($query)->result();
			
		} else {
			// Statistik per unit
			$data['total_unit'] = 1; // Hanya 1 unit yang dipilih
			
			$data['total_pegawai'] = $this->db->where('unit', $selected_unit)
										->count_all_results('data_pegawai');
			
			$data['pegawai_tetap'] = $this->db->where('unit', $selected_unit)
										->where('status', 'Karyawan Tetap')
										->count_all_results('data_pegawai');
			
			$data['pegawai_tidak_tetap'] = $this->db->where('unit', $selected_unit)
											->where('status', 'Karyawan Tidak Tetap')
											->count_all_results('data_pegawai');
			
			// Statistik per kategori di unit ini
			$query = "SELECT j.kategori, COUNT(*) as jumlah 
					  FROM data_pegawai p 
					  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
					  WHERE p.unit = '$selected_unit'
					  GROUP BY j.kategori";
			$data['per_kategori'] = $this->db->query($query)->result();
		}
		
		// Data untuk chart jenis kelamin
		if($selected_unit == 'ALL') {
			$data['jk_laki'] = $this->db->where('jenis_kelamin', 'Laki-Laki')
									->count_all_results('data_pegawai');
			$data['jk_perempuan'] = $this->db->where('jenis_kelamin', 'Perempuan')
									->count_all_results('data_pegawai');
		} else {
			$data['jk_laki'] = $this->db->where('unit', $selected_unit)
									->where('jenis_kelamin', 'Laki-Laki')
									->count_all_results('data_pegawai');
			$data['jk_perempuan'] = $this->db->where('unit', $selected_unit)
									->where('jenis_kelamin', 'Perempuan')
									->count_all_results('data_pegawai');
		}
		
		// Data untuk chart status kepegawaian
		if($selected_unit == 'ALL') {
			$query_status = "SELECT status_kepegawaian, COUNT(*) as jumlah 
							 FROM data_pegawai 
							 GROUP BY status_kepegawaian";
		} else {
			$query_status = "SELECT status_kepegawaian, COUNT(*) as jumlah 
							 FROM data_pegawai 
							 WHERE unit = '$selected_unit'
							 GROUP BY status_kepegawaian";
		}
		$data['per_status'] = $this->db->query($query_status)->result();
		
		// Data absensi bulan ini
		$bulan_ini = date('m') . date('Y');
		if($selected_unit == 'ALL') {
			$data['absensi_bulan_ini'] = $this->db->where('bulan', $bulan_ini)
											->count_all_results('data_kehadiran');
		} else {
			$data['absensi_bulan_ini'] = $this->db->where('unit', $selected_unit)
											->where('bulan', $bulan_ini)
											->count_all_results('data_kehadiran');
		}
		
		// Top 5 unit berdasarkan jumlah pegawai (Super Admin only)
		if($hak_akses == 1 && $selected_unit == 'ALL') {
			$query_top_unit = "SELECT u.nama_unit, u.kode_unit, COUNT(p.id_pegawai) as jumlah
							   FROM data_unit u
							   LEFT JOIN data_pegawai p ON u.kode_unit = p.unit
							   WHERE u.kode_unit != 'YYS' AND u.status = 'Aktif'
							   GROUP BY u.kode_unit, u.nama_unit
							   ORDER BY jumlah DESC
							   LIMIT 5";
			$data['top_units'] = $this->db->query($query_top_unit)->result();
		} else {
			$data['top_units'] = array();
		}
		
		// Nama unit yang dipilih
		if($selected_unit != 'ALL') {
			$unit_info = $this->ModelYayasan->get_unit_by_code($selected_unit);
			$data['nama_unit'] = $unit_info ? $unit_info->nama_unit : '';
		} else {
			$data['nama_unit'] = 'Semua Unit Yayasan';
		}

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/dashboard_multiunit', $data);
		$this->load->view('template_admin/footer');
	}

	// AJAX endpoint untuk update chart
	public function get_chart_data()
	{
		$unit = $this->input->get('unit');
		$chart_type = $this->input->get('type');
		
		$response = array();
		
		switch($chart_type) {
			case 'kategori':
				if($unit == 'ALL') {
					$query = "SELECT j.kategori, COUNT(*) as jumlah 
							  FROM data_pegawai p 
							  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
							  GROUP BY j.kategori";
				} else {
					$query = "SELECT j.kategori, COUNT(*) as jumlah 
							  FROM data_pegawai p 
							  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
							  WHERE p.unit = '$unit'
							  GROUP BY j.kategori";
				}
				$response = $this->db->query($query)->result();
				break;
				
			case 'jenis_kelamin':
				if($unit == 'ALL') {
					$laki = $this->db->where('jenis_kelamin', 'Laki-Laki')->count_all_results('data_pegawai');
					$perempuan = $this->db->where('jenis_kelamin', 'Perempuan')->count_all_results('data_pegawai');
				} else {
					$laki = $this->db->where('unit', $unit)->where('jenis_kelamin', 'Laki-Laki')->count_all_results('data_pegawai');
					$perempuan = $this->db->where('unit', $unit)->where('jenis_kelamin', 'Perempuan')->count_all_results('data_pegawai');
				}
				$response = array('laki' => $laki, 'perempuan' => $perempuan);
				break;
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}