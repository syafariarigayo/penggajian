<?php

class Data_Pegawai extends CI_Controller {

	public function __construct(){
		parent::__construct();

		// Izinkan hak_akses 1 (Super Admin) dan 3 (Kepala Unit/Admin Unit)
		$hak = $this->session->userdata('hak_akses');
		if($hak != '1' && $hak != '3'){
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Anda Belum Login!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('login');
		}
	}

	// Helper: ambil unit yang sedang login (null jika Super Admin)
	private function _get_my_unit() {
		$hak = $this->session->userdata('hak_akses');
		if($hak == '1') return null; // Super Admin -> semua unit
		$id = $this->session->userdata('id_pegawai');
		$p  = $this->db->get_where('data_pegawai', array('id_pegawai' => $id))->row();
		return $p ? $p->unit : null;
	}

	public function index()
	{
		$data['title']    = "Data Pegawai";
		$my_unit          = $this->_get_my_unit();
		$data['my_unit']  = $my_unit;
		$data['hak_akses']= $this->session->userdata('hak_akses');

		if($my_unit) {
			// Kepala unit: hanya pegawai di unitnya
			$data['pegawai'] = $this->db->where('unit', $my_unit)
										->get('data_pegawai')->result();
			// Nama unit untuk ditampilkan
			$unit_info = $this->ModelYayasan->get_unit_by_code($my_unit);
			$data['nama_unit'] = $unit_info ? $unit_info->nama_unit : $my_unit;
		} else {
			// Super Admin: semua pegawai
			$data['pegawai'] = $this->ModelPenggajian->get_data('data_pegawai')->result();
			$data['nama_unit'] = 'Semua Unit';
		}

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/pegawai/data_pegawai', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_data()
	{
		$data['title']    = "Tambah Data Pegawai";
		$data['jabatan']  = $this->ModelPenggajian->get_data('data_jabatan')->result();
		$my_unit          = $this->_get_my_unit();
		$data['my_unit']  = $my_unit;
		$data['hak_akses']= $this->session->userdata('hak_akses');

		// Daftar unit: Super Admin lihat semua, kepala unit hanya unitnya
		if($my_unit) {
			$unit_obj = $this->ModelYayasan->get_unit_by_code($my_unit);
			$data['units'] = $unit_obj ? array($unit_obj) : array();
		} else {
			$data['units'] = $this->ModelYayasan->get_active_units();
		}

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/pegawai/tambah_dataPegawai', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_data_aksi() {
		$this->_rules();

		if($this->form_validation->run() == FALSE) {
			$this->tambah_data();
		} else {
			$my_unit = $this->_get_my_unit();

			$nik           = $this->input->post('nik');
			$nama_pegawai  = $this->input->post('nama_pegawai');
			$username      = $this->input->post('username');
			$password      = md5($this->input->post('password'));
			$jenis_kelamin = $this->input->post('jenis_kelamin');
			$jabatan       = $this->input->post('jabatan');
			$tanggal_masuk = $this->input->post('tanggal_masuk');
			$status        = $this->input->post('status');
			$hak_akses     = $this->input->post('hak_akses');
			// Unit: kepala unit dipaksa pakai unitnya sendiri, Super Admin bebas pilih
			$unit = $my_unit ? $my_unit : $this->input->post('unit');

			$photo = $_FILES['photo']['name'];
			if($photo != '') {
				$config['upload_path']   = './photo';
				$config['allowed_types'] = 'jpg|jpeg|png|tiff';
				$config['max_size']      = 2048;
				$config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()),0,10);
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload('photo')){
					echo "Photo Gagal Diupload !";
					return;
				} else {
					$photo = $this->upload->data('file_name');
				}
			}

			$data = array(
				'nik'           => $nik,
				'nama_pegawai'  => $nama_pegawai,
				'username'      => $username,
				'password'      => $password,
				'jenis_kelamin' => $jenis_kelamin,
				'jabatan'       => $jabatan,
				'tanggal_masuk' => $tanggal_masuk,
				'status'        => $status,
				'hak_akses'     => $hak_akses,
				'unit'          => $unit,
				'photo'         => $photo,
			);

			$this->ModelPenggajian->insert_data($data, 'data_pegawai');
			$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Data berhasil ditambahkan!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_pegawai');
		}
	}

	public function update_data($id)
	{
		// Kepala unit hanya boleh edit pegawai di unitnya
		$my_unit = $this->_get_my_unit();
		if($my_unit) {
			$cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
			if($cek == 0) {
				$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Akses Ditolak!</strong> Anda tidak dapat mengedit pegawai dari unit lain.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>');
				redirect('admin/data_pegawai');
			}
		}

		$data['title']    = "Update Data Pegawai";
		$data['jabatan']  = $this->ModelPenggajian->get_data('data_jabatan')->result();
		$data['pegawai']  = $this->db->query("SELECT * FROM data_pegawai WHERE id_pegawai='$id'")->result();
		$data['my_unit']  = $my_unit;
		$data['hak_akses']= $this->session->userdata('hak_akses');

		if($my_unit) {
			$unit_obj = $this->ModelYayasan->get_unit_by_code($my_unit);
			$data['units'] = $unit_obj ? array($unit_obj) : array();
		} else {
			$data['units'] = $this->ModelYayasan->get_active_units();
		}

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/pegawai/update_dataPegawai', $data);
		$this->load->view('template_admin/footer');
	}

	public function update_data_aksi() {
		$this->_rules();

		if($this->form_validation->run() == FALSE) {
			$id = $this->input->post('id_pegawai');
			$this->update_data($id);
		} else {
			$my_unit = $this->_get_my_unit();
			$id      = $this->input->post('id_pegawai');

			// Keamanan: kepala unit tidak bisa update pegawai unit lain
			if($my_unit) {
				$cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
				if($cek == 0) {
					redirect('admin/data_pegawai');
				}
			}

			$nik           = $this->input->post('nik');
			$nama_pegawai  = $this->input->post('nama_pegawai');
			$username      = $this->input->post('username');
			$password      = md5($this->input->post('password'));
			$jenis_kelamin = $this->input->post('jenis_kelamin');
			$jabatan       = $this->input->post('jabatan');
			$tanggal_masuk = $this->input->post('tanggal_masuk');
			$status        = $this->input->post('status');
			$hak_akses     = $this->input->post('hak_akses');
			$unit          = $my_unit ? $my_unit : $this->input->post('unit');

			$photo = $_FILES['photo']['name'];
			if($photo) {
				$config['upload_path']   = './photo';
				$config['allowed_types'] = 'jpg|jpeg|png|tiff';
				$config['max_size']      = 2048;
				$config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()),0,10);
				$this->load->library('upload', $config);
				if($this->upload->do_upload('photo')){
					$photo = $this->upload->data('file_name');
					$this->db->set('photo', $photo);
				} else {
					echo $this->upload->display_errors();
				}
			}

			$data = array(
				'nik'           => $nik,
				'nama_pegawai'  => $nama_pegawai,
				'username'      => $username,
				'password'      => $password,
				'jenis_kelamin' => $jenis_kelamin,
				'jabatan'       => $jabatan,
				'tanggal_masuk' => $tanggal_masuk,
				'status'        => $status,
				'hak_akses'     => $hak_akses,
				'unit'          => $unit,
			);

			$where = array('id_pegawai' => $id);
			$this->ModelPenggajian->update_data('data_pegawai', $data, $where);
			$this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Data berhasil diupdate!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>');
			redirect('admin/data_pegawai');
		}
	}

	public function _rules() {
		$this->form_validation->set_rules('nik',           'NIK',           'required');
		$this->form_validation->set_rules('nama_pegawai',  'Nama Pegawai',  'required');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
		$this->form_validation->set_rules('jabatan',       'Jabatan',       'required');
		$this->form_validation->set_rules('status',        'Status',        'required');
	}

	public function delete_data($id) {
		$my_unit = $this->_get_my_unit();

		// Kepala unit hanya boleh hapus pegawai di unitnya
		if($my_unit) {
			$cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
			if($cek == 0) {
				$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Akses Ditolak!</strong> Anda tidak dapat menghapus pegawai dari unit lain.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>');
				redirect('admin/data_pegawai');
			}
		}

		$where = array('id_pegawai' => $id);
		$this->ModelPenggajian->delete_data($where, 'data_pegawai');
		$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Data berhasil dihapus!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>');
		redirect('admin/data_pegawai');
	}
}
?>