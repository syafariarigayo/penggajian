<?php

class ModelYayasan extends CI_model{

	// =====================================================
	// FUNGSI UNIT PENDIDIKAN
	// =====================================================

	public function get_all_units() {
		return $this->db->get('data_unit')->result();
	}

	public function get_active_units() {
		$this->db->where('status', 'Aktif');
		return $this->db->get('data_unit')->result();
	}

	public function get_unit_by_code($kode_unit) {
		$this->db->where('kode_unit', $kode_unit);
		return $this->db->get('data_unit')->row();
	}

	// =====================================================
	// FUNGSI PEGAWAI BERDASARKAN UNIT
	// =====================================================

	public function get_pegawai_by_unit($kode_unit) {
		$this->db->where('unit', $kode_unit);
		return $this->db->get('data_pegawai')->result();
	}

	public function count_pegawai_by_unit($kode_unit) {
		$this->db->where('unit', $kode_unit);
		return $this->db->count_all_results('data_pegawai');
	}

	// Get pegawai by kategori (Pendidik/Tendik)
	public function get_pegawai_by_kategori($kode_unit, $kategori) {
		$this->db->select('data_pegawai.*, data_jabatan.kategori');
		$this->db->from('data_pegawai');
		$this->db->join('data_jabatan', 'data_pegawai.jabatan = data_jabatan.nama_jabatan');
		$this->db->where('data_pegawai.unit', $kode_unit);
		$this->db->where('data_jabatan.kategori', $kategori);
		return $this->db->get()->result();
	}

	// =====================================================
	// FUNGSI ABSENSI BERDASARKAN UNIT
	// =====================================================

	public function get_absensi_by_unit($kode_unit, $bulan) {
		$this->db->where('unit', $kode_unit);
		$this->db->where('bulan', $bulan);
		return $this->db->get('data_kehadiran')->result();
	}

	// =====================================================
	// FUNGSI PENILAIAN BERDASARKAN UNIT
	// =====================================================

	public function get_penilaian_by_unit($kode_unit, $bulan) {
		$this->db->where('unit', $kode_unit);
		$this->db->where('bulan', $bulan);
		return $this->db->get('data_penilaian')->result();
	}

	// =====================================================
	// FUNGSI GAJI BERDASARKAN UNIT
	// =====================================================

	public function get_gaji_by_unit($kode_unit, $bulan) {
		$query = "SELECT data_pegawai.nik, data_pegawai.nama_pegawai,
			data_pegawai.jenis_kelamin, data_pegawai.unit,
			data_jabatan.nama_jabatan, data_jabatan.gaji_pokok,
			data_jabatan.tj_transport, data_jabatan.uang_makan,
			data_jabatan.tj_fungsional, data_jabatan.tj_sertifikasi,
			data_kehadiran.alpha, data_kehadiran.hadir
			FROM data_pegawai
			INNER JOIN data_kehadiran ON data_kehadiran.nik=data_pegawai.nik
			INNER JOIN data_jabatan ON data_jabatan.nama_jabatan=data_pegawai.jabatan
			WHERE data_pegawai.unit='$kode_unit' AND data_kehadiran.bulan='$bulan'
			ORDER BY data_pegawai.nama_pegawai ASC";
		return $this->db->query($query)->result();
	}

	// Get total gaji per unit
	public function get_total_gaji_unit($kode_unit, $bulan) {
		$query = "SELECT 
			SUM(data_jabatan.gaji_pokok + data_jabatan.tj_transport + 
			    data_jabatan.uang_makan + data_jabatan.tj_fungsional + 
			    data_jabatan.tj_sertifikasi) as total_gaji
			FROM data_pegawai
			INNER JOIN data_kehadiran ON data_kehadiran.nik=data_pegawai.nik
			INNER JOIN data_jabatan ON data_jabatan.nama_jabatan=data_pegawai.jabatan
			WHERE data_pegawai.unit='$kode_unit' AND data_kehadiran.bulan='$bulan'";
		return $this->db->query($query)->row();
	}

	// =====================================================
	// FUNGSI LAPORAN KONSOLIDASI
	// =====================================================

	public function get_konsolidasi_gaji($bulan) {
		$query = "SELECT 
			u.kode_unit,
			u.nama_unit,
			COUNT(DISTINCT p.nik) as jumlah_pegawai,
			SUM(j.gaji_pokok) as total_gaji_pokok,
			SUM(j.tj_transport) as total_tj_transport,
			SUM(j.uang_makan) as total_uang_makan,
			SUM(j.tj_fungsional) as total_tj_fungsional,
			SUM(j.tj_sertifikasi) as total_tj_sertifikasi,
			SUM(j.gaji_pokok + j.tj_transport + j.uang_makan + 
			    j.tj_fungsional + j.tj_sertifikasi) as total_keseluruhan
			FROM data_unit u
			LEFT JOIN data_pegawai p ON u.kode_unit = p.unit
			LEFT JOIN data_kehadiran k ON p.nik = k.nik
			LEFT JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
			WHERE k.bulan = '$bulan' OR k.bulan IS NULL
			GROUP BY u.kode_unit, u.nama_unit
			ORDER BY u.id_unit ASC";
		return $this->db->query($query)->result();
	}

	// Get statistik pegawai per unit
	public function get_statistik_pegawai() {
		$query = "SELECT 
			p.unit,
			u.nama_unit,
			j.kategori,
			COUNT(*) as jumlah
			FROM data_pegawai p
			INNER JOIN data_unit u ON p.unit = u.kode_unit
			INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
			WHERE p.status = 'Karyawan Tetap'
			GROUP BY p.unit, u.nama_unit, j.kategori
			ORDER BY u.id_unit, j.kategori";
		return $this->db->query($query)->result();
	}

	// =====================================================
	// FUNGSI CEK HAK AKSES
	// =====================================================

	public function cek_akses_unit($id_pegawai, $kode_unit) {
		// Ambil data pegawai
		$pegawai = $this->db->get_where('data_pegawai', array('id_pegawai' => $id_pegawai))->row();
		
		if(!$pegawai) {
			return false;
		}

		// Level 1 (Super Admin) bisa akses semua
		if($pegawai->hak_akses == 1) {
			return true;
		}

		// Level 2-7 (Admin Unit) hanya bisa akses unit sendiri
		if($pegawai->hak_akses >= 2 && $pegawai->hak_akses <= 7) {
			return ($pegawai->unit == $kode_unit);
		}

		// Level lainnya tidak bisa akses
		return false;
	}

	// Get unit yang bisa diakses user
	public function get_unit_akses($id_pegawai) {
		$pegawai = $this->db->get_where('data_pegawai', array('id_pegawai' => $id_pegawai))->row();
		
		if(!$pegawai) {
			return array();
		}

		// Super Admin bisa akses semua unit
		if($pegawai->hak_akses == 1) {
			return $this->get_all_units();
		}

		// Admin Unit hanya bisa akses unit sendiri
		if($pegawai->hak_akses >= 2 && $pegawai->hak_akses <= 7) {
			$this->db->where('kode_unit', $pegawai->unit);
			return $this->db->get('data_unit')->result();
		}

		// Level lainnya tidak ada akses
		return array();
	}

	// =====================================================
	// FUNGSI TUNJANGAN TAMBAHAN
	// =====================================================

	public function get_tunjangan($nik, $bulan) {
		$this->db->where('nik', $nik);
		$this->db->where('bulan', $bulan);
		return $this->db->get('data_tunjangan')->result();
	}

	public function insert_tunjangan($data) {
		return $this->db->insert('data_tunjangan', $data);
	}

	// =====================================================
	// FUNGSI DASHBOARD STATISTIK
	// =====================================================

	public function get_dashboard_stats($kode_unit = 'ALL') {
		$stats = array();

		if($kode_unit == 'ALL') {
			// Statistik untuk semua unit
			$stats['total_pegawai'] = $this->db->count_all('data_pegawai');
			$stats['total_unit'] = $this->db->where('kode_unit !=', 'YYS')->count_all_results('data_unit');
			
			// Total per kategori
			$query = "SELECT j.kategori, COUNT(*) as jumlah 
					  FROM data_pegawai p 
					  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
					  GROUP BY j.kategori";
			$stats['per_kategori'] = $this->db->query($query)->result();
			
		} else {
			// Statistik untuk unit tertentu
			$stats['total_pegawai'] = $this->db->where('unit', $kode_unit)->count_all_results('data_pegawai');
			
			// Total per kategori di unit ini
			$query = "SELECT j.kategori, COUNT(*) as jumlah 
					  FROM data_pegawai p 
					  INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan 
					  WHERE p.unit = '$kode_unit'
					  GROUP BY j.kategori";
			$stats['per_kategori'] = $this->db->query($query)->result();
		}

		return $stats;
	}

	// =====================================================
	// FUNGSI LAPORAN KOMPARASI ANTAR UNIT
	// =====================================================

	public function get_komparasi_unit($bulan) {
		$query = "SELECT 
			u.nama_unit,
			COUNT(DISTINCT CASE WHEN j.kategori = 'Pendidik' THEN p.nik END) as jml_pendidik,
			COUNT(DISTINCT CASE WHEN j.kategori = 'Tendik' THEN p.nik END) as jml_tendik,
			COUNT(DISTINCT CASE WHEN j.kategori = 'Manajemen' THEN p.nik END) as jml_manajemen,
			AVG(CASE WHEN pn.total_nilai IS NOT NULL THEN pn.total_nilai ELSE 0 END) as rata_penilaian,
			SUM(j.gaji_pokok + j.tj_transport + j.uang_makan + j.tj_fungsional + j.tj_sertifikasi) as total_gaji
			FROM data_unit u
			LEFT JOIN data_pegawai p ON u.kode_unit = p.unit
			LEFT JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
			LEFT JOIN data_penilaian pn ON p.nik = pn.nik AND pn.bulan = '$bulan'
			WHERE u.kode_unit != 'YYS'
			GROUP BY u.kode_unit, u.nama_unit
			ORDER BY u.id_unit";
		return $this->db->query($query)->result();
	}
}

?>