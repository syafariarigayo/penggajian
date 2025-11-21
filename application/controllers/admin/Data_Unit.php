<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Unit extends CI_Controller {

	public function __construct(){
		parent::__construct();

		// Cek hak akses - hanya Super Admin (level 1) yang bisa akses
		if($this->session->userdata('hak_akses') != '1'){
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Akses Ditolak!</strong> Anda tidak memiliki hak akses ke halaman ini.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/dashboard');
		}
	}
	
	public function index() 
	{
		$data['title'] = "Data Unit Pendidikan";
		$data['units'] = $this->ModelYayasan->get_all_units();

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/unit/data_unit', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_unit() 
	{
		$data['title'] = "Tambah Unit Pendidikan";
		
		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/unit/tambah_unit', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_unit_aksi() 
	{
		$this->_rules_tambah();

		if($this->form_validation->run() == FALSE) {
			$this->tambah_unit();
		} else {
			// Ambil data dari form
			$kode_unit = strtoupper($this->input->post('kode_unit'));
			$nama_unit = $this->input->post('nama_unit');
			$jenjang = $this->input->post('jenjang');
			$kepala_unit = $this->input->post('kepala_unit');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$status = $this->input->post('status');

			// Cek apakah kode unit sudah ada
			$cek_kode = $this->db->get_where('data_unit', array('kode_unit' => $kode_unit))->num_rows();
			
			if($cek_kode > 0) {
				$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Gagal!</strong> Kode unit sudah digunakan. Gunakan kode yang berbeda.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>');
				redirect('admin/data_unit/tambah_unit');
			}

			$data = array(
				'kode_unit' => $kode_unit,
				'nama_unit' => $nama_unit,
				'jenjang' => $jenjang,
				'kepala_unit' => $kepala_unit,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'status' => $status
			);

			$this->ModelPenggajian->insert_data($data, 'data_unit');
			
			$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Berhasil!</strong> Unit pendidikan berhasil ditambahkan.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_unit');
		}
	}

	public function update_unit($id) 
	{
		$data['title'] = "Update Data Unit";
		$data['unit'] = $this->db->query("SELECT * FROM data_unit WHERE id_unit='$id'")->result();
		
		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/unit/update_unit', $data);
		$this->load->view('template_admin/footer');
	}

	public function update_unit_aksi() 
	{
		$this->_rules_update();

		if($this->form_validation->run() == FALSE) {
			$id = $this->input->post('id_unit');
			$this->update_unit($id);
		} else {
			$id = $this->input->post('id_unit');
			$kode_unit = strtoupper($this->input->post('kode_unit'));
			$nama_unit = $this->input->post('nama_unit');
			$jenjang = $this->input->post('jenjang');
			$kepala_unit = $this->input->post('kepala_unit');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$status = $this->input->post('status');

			// Cek apakah kode unit sudah digunakan unit lain
			$cek_kode = $this->db->where('kode_unit', $kode_unit)
							->where('id_unit !=', $id)
							->get('data_unit')
							->num_rows();
			
			if($cek_kode > 0) {
				$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Gagal!</strong> Kode unit sudah digunakan unit lain.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>');
				redirect('admin/data_unit/update_unit/'.$id);
			}

			// Get kode unit lama untuk update data pegawai
			$unit_lama = $this->db->select('kode_unit')
								->where('id_unit', $id)
								->get('data_unit')
								->row();

			$data = array(
				'kode_unit' => $kode_unit,
				'nama_unit' => $nama_unit,
				'jenjang' => $jenjang,
				'kepala_unit' => $kepala_unit,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'status' => $status
			);

			$where = array('id_unit' => $id);

			$this->ModelPenggajian->update_data('data_unit', $data, $where);

			// Update kode unit di tabel pegawai jika kode berubah
			if($unit_lama->kode_unit != $kode_unit) {
				$this->db->where('unit', $unit_lama->kode_unit);
				$this->db->update('data_pegawai', array('unit' => $kode_unit));
			}

			$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Berhasil!</strong> Data unit berhasil diupdate.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_unit');
		}
	}

	public function detail_unit($kode_unit)
	{
		$data['title'] = "Detail Unit Pendidikan";
		$data['unit'] = $this->ModelYayasan->get_unit_by_code($kode_unit);
		
		if(!$data['unit']) {
			show_404();
		}

		// Get pegawai di unit ini
		$data['pegawai'] = $this->ModelYayasan->get_pegawai_by_unit($kode_unit);

		// Get statistik
		$data['stats'] = $this->ModelYayasan->get_dashboard_stats($kode_unit);

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/unit/detail_unit', $data);
		$this->load->view('template_admin/footer');
	}

	public function delete_unit($id) 
	{
		// Cek apakah unit ini yayasan
		$unit = $this->db->get_where('data_unit', array('id_unit' => $id))->row();
		
		if($unit->kode_unit == 'YYS') {
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Gagal!</strong> Unit Yayasan tidak boleh dihapus.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_unit');
		}

		// Cek apakah ada pegawai di unit ini
		$cek_pegawai = $this->db->where('unit', $unit->kode_unit)
							->count_all_results('data_pegawai');
		
		if($cek_pegawai > 0) {
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Gagal!</strong> Unit masih memiliki '.$cek_pegawai.' pegawai. Pindahkan pegawai terlebih dahulu.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_unit');
		}

		$where = array('id_unit' => $id);
		$this->ModelPenggajian->delete_data($where, 'data_unit');
		
		$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Berhasil!</strong> Unit pendidikan berhasil dihapus.
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>');
		redirect('admin/data_unit');
	}

	// Validation rules
	private function _rules_tambah() 
	{
		$this->form_validation->set_rules('kode_unit', 'Kode Unit', 'required|max_length[10]|alpha_dash', array(
			'required' => 'Kode unit harus diisi',
			'max_length' => 'Kode unit maksimal 10 karakter',
			'alpha_dash' => 'Kode unit hanya boleh huruf dan angka'
		));
		$this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required', array(
			'required' => 'Nama unit harus diisi'
		));
		$this->form_validation->set_rules('jenjang', 'Jenjang', 'required', array(
			'required' => 'Jenjang pendidikan harus dipilih'
		));
		$this->form_validation->set_rules('status', 'Status', 'required', array(
			'required' => 'Status unit harus dipilih'
		));
	}

	private function _rules_update() 
	{
		$this->form_validation->set_rules('kode_unit', 'Kode Unit', 'required|max_length[10]|alpha_dash', array(
			'required' => 'Kode unit harus diisi',
			'max_length' => 'Kode unit maksimal 10 karakter',
			'alpha_dash' => 'Kode unit hanya boleh huruf dan angka'
		));
		$this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required', array(
			'required' => 'Nama unit harus diisi'
		));
		$this->form_validation->set_rules('jenjang', 'Jenjang', 'required', array(
			'required' => 'Jenjang pendidikan harus dipilih'
		));
		$this->form_validation->set_rules('status', 'Status', 'required', array(
			'required' => 'Status unit harus dipilih'
		));
	}
}