<?php

class Penilaian_Karyawan extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->session->userdata('hak_akses') != '1'){
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
		$data['title'] = "Data Penilaian Karyawan";

		if((isset($_GET['bulan']) && $_GET['bulan']!='') && (isset($_GET['tahun']) && $_GET['tahun']!='')){
			$bulan = $_GET['bulan'];
			$tahun = $_GET['tahun'];
			$bulantahun = $bulan.$tahun;
		}else{
			$bulan = date('m');
			$tahun = date('Y');
			$bulantahun = $bulan.$tahun;
		}

		$data['penilaian'] = $this->db->query("SELECT * FROM data_penilaian WHERE bulan='$bulantahun' ORDER BY nama_pegawai ASC")->result();

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/penilaian/data_penilaian', $data);
		$this->load->view('template_admin/footer');
	}

	public function input_penilaian()
	{
		if($this->input->post('submit', TRUE) == 'submit') {
			$post = $this->input->post();

			foreach ($post['bulan'] as $key => $value) {
				if($post['bulan'][$key] !='' || $post['nik'][$key] !='')
				{
					// Hitung total nilai
					$total = $post['kedisiplinan'][$key] + $post['kerjasama'][$key] + 
					         $post['tanggung_jawab'][$key] + $post['kualitas_kerja'][$key];
					
					// Tentukan kategori
					if($total >= 340) {
						$kategori = "Sangat Baik";
					} elseif($total >= 260) {
						$kategori = "Baik";
					} elseif($total >= 180) {
						$kategori = "Cukup";
					} else {
						$kategori = "Kurang";
					}

					$simpan[] = array(
						'bulan'				=> $post['bulan'][$key],
						'nik'				=> $post['nik'][$key],
						'nama_pegawai'		=> $post['nama_pegawai'][$key],
						'jabatan'			=> $post['jabatan'][$key],
						'kedisiplinan'		=> $post['kedisiplinan'][$key],
						'kerjasama'			=> $post['kerjasama'][$key],
						'tanggung_jawab'	=> $post['tanggung_jawab'][$key],
						'kualitas_kerja'	=> $post['kualitas_kerja'][$key],
						'total_nilai'		=> $total,
						'kategori'			=> $kategori,
					);
				}
			}
			$this->ModelPenggajian->insert_batch('data_penilaian', $simpan);
			$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Data penilaian berhasil ditambahkan!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/penilaian_karyawan');
		}

		$data['title'] = "Form Input Penilaian Karyawan";

		if((isset($_GET['bulan']) && $_GET['bulan']!='') && (isset($_GET['tahun']) && $_GET['tahun']!='')){
			$bulan = $_GET['bulan'];
			$tahun = $_GET['tahun'];
			$bulantahun = $bulan.$tahun;
		}else{
			$bulan = date('m');
			$tahun = date('Y');
			$bulantahun = $bulan.$tahun;
		}
		
		$data['input_penilaian'] = $this->db->query("SELECT data_pegawai.*, data_jabatan.nama_jabatan FROM data_pegawai
			INNER JOIN data_jabatan ON data_pegawai.jabatan = data_jabatan.nama_jabatan
			WHERE NOT EXISTS (SELECT * FROM data_penilaian WHERE bulan='$bulantahun' AND data_pegawai.nik=data_penilaian.nik) 
			ORDER BY data_pegawai.nama_pegawai ASC")->result();
			
		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/penilaian/tambah_penilaian', $data);
		$this->load->view('template_admin/footer');
	}

	public function edit_penilaian($id)
	{
		$data['title'] = "Edit Penilaian Karyawan";
		$data['penilaian'] = $this->db->query("SELECT * FROM data_penilaian WHERE id_penilaian='$id'")->result();
		
		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/penilaian/edit_penilaian', $data);
		$this->load->view('template_admin/footer');
	}

	public function edit_penilaian_aksi()
	{
		$id					= $this->input->post('id_penilaian');
		$kedisiplinan		= $this->input->post('kedisiplinan');
		$kerjasama			= $this->input->post('kerjasama');
		$tanggung_jawab		= $this->input->post('tanggung_jawab');
		$kualitas_kerja		= $this->input->post('kualitas_kerja');
		
		// Hitung total nilai
		$total = $kedisiplinan + $kerjasama + $tanggung_jawab + $kualitas_kerja;
		
		// Tentukan kategori
		if($total >= 340) {
			$kategori = "Sangat Baik";
		} elseif($total >= 260) {
			$kategori = "Baik";
		} elseif($total >= 180) {
			$kategori = "Cukup";
		} else {
			$kategori = "Kurang";
		}

		$data = array(
			'kedisiplinan'		=> $kedisiplinan,
			'kerjasama'			=> $kerjasama,
			'tanggung_jawab'	=> $tanggung_jawab,
			'kualitas_kerja'	=> $kualitas_kerja,
			'total_nilai'		=> $total,
			'kategori'			=> $kategori,
		);

		$where = array('id_penilaian' => $id);

		$this->ModelPenggajian->update_data('data_penilaian', $data, $where);
		$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Data penilaian berhasil diupdate!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>');
		redirect('admin/penilaian_karyawan');
	}

	public function delete_penilaian($id)
	{
		$where = array('id_penilaian' => $id);
		$this->ModelPenggajian->delete_data($where, 'data_penilaian');
		$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Data penilaian berhasil dihapus!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>');
		redirect('admin/penilaian_karyawan');
	}

	public function laporan_penilaian()
	{
		$data['title'] = "Laporan Penilaian Karyawan";

		$this->load->view('template_admin/header',$data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/penilaian/laporan_penilaian');
		$this->load->view('template_admin/footer');
	}

	public function cetak_laporan_penilaian()
	{
		$data['title'] = "Cetak Laporan Penilaian Karyawan";
		
		if((isset($_GET['bulan']) && $_GET['bulan']!='') && (isset($_GET['tahun']) && $_GET['tahun']!='')){
			$bulan = $_GET['bulan'];
			$tahun = $_GET['tahun'];
			$bulantahun = $bulan.$tahun;
		}else{
			$bulan = date('m');
			$tahun = date('Y');
			$bulantahun = $bulan.$tahun;
		}
		
		$data['lap_penilaian'] = $this->db->query("SELECT * FROM data_penilaian WHERE bulan='$bulantahun' ORDER BY nama_pegawai ASC")->result();
		$this->load->view('template_admin/header',$data);
		$this->load->view('admin/penilaian/cetak_penilaian', $data);
	}
}

?>