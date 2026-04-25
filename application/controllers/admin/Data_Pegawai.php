<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Pegawai extends CI_Controller {

    public function __construct(){
        parent::__construct();

        // Cek login: izinkan hak_akses 1 (Super Admin) dan 3 (Kepala Unit)
        $hak = $this->session->userdata('hak_akses');
        if($hak != '1' && $hak != '3') {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    // ─────────────────────────────────────────────────────────
    // Helper: ambil unit milik user yang login
    // null  = Super Admin (akses semua)
    // string = kode unit kepala unit
    // ─────────────────────────────────────────────────────────
    private function _my_unit()
    {
        $hak = $this->session->userdata('hak_akses');
        if($hak == '1') return null;

        $id = $this->session->userdata('id_pegawai');
        if(!$id) return null;

        $p = $this->db->get_where('data_pegawai', array('id_pegawai' => $id))->row();
        return ($p && isset($p->unit)) ? $p->unit : null;
    }

    // ─────────────────────────────────────────────────────────
    // Helper: ambil daftar kolom yang ada di tabel data_pegawai
    // ─────────────────────────────────────────────────────────
    private function _kolom_ada()
    {
        static $cache = null;
        if($cache === null) {
            $cache = array();
            $q = $this->db->query("SHOW COLUMNS FROM data_pegawai");
            foreach($q->result() as $row) {
                $cache[] = $row->Field;
            }
        }
        return $cache;
    }

    // ─────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $data['title']     = "Data Pegawai";
        $data['hak_akses'] = $this->session->userdata('hak_akses');
        $my_unit           = $this->_my_unit();
        $data['my_unit']   = $my_unit;

        if($my_unit) {
            $data['pegawai']   = $this->db->where('unit', $my_unit)->get('data_pegawai')->result();
            $unit_info         = $this->ModelYayasan->get_unit_by_code($my_unit);
            $data['nama_unit'] = $unit_info ? $unit_info->nama_unit : $my_unit;
        } else {
            $data['pegawai']   = $this->ModelPenggajian->get_data('data_pegawai')->result();
            $data['nama_unit'] = 'Semua Unit';
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/data_pegawai', $data);
        $this->load->view('template_admin/footer');
    }

    // ─────────────────────────────────────────────────────────
    // TAMBAH DATA
    // ─────────────────────────────────────────────────────────
    public function tambah_data()
    {
        $data['title']     = "Tambah Data Pegawai";
        $data['jabatan']   = $this->ModelPenggajian->get_data('data_jabatan')->result();
        $my_unit           = $this->_my_unit();
        $data['my_unit']   = $my_unit;
        $data['hak_akses'] = $this->session->userdata('hak_akses');

        if($my_unit) {
            $unit_obj      = $this->ModelYayasan->get_unit_by_code($my_unit);
            $data['units'] = $unit_obj ? array($unit_obj) : array();
        } else {
            $data['units'] = $this->ModelYayasan->get_active_units();
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/tambah_dataPegawai', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_data_aksi()
    {
        $this->_rules();

        if($this->form_validation->run() == FALSE) {
            $this->tambah_data();
        } else {
            $my_unit = $this->_my_unit();
            $unit    = $my_unit ? $my_unit : $this->input->post('unit');

            // Upload photo
            $photo = '';
            if(!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = './photo';
                $config['allowed_types'] = 'jpg|jpeg|png|tiff';
                $config['max_size']      = 2048;
                $config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()), 0, 10);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')) {
                    $photo = $this->upload->data('file_name');
                }
            }

            // Kolom dasar
            $data = array(
                'nik'           => $this->input->post('nik'),
                'nama_pegawai'  => $this->input->post('nama_pegawai'),
                'username'      => $this->input->post('username'),
                'password'      => md5($this->input->post('password')),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan'       => $this->input->post('jabatan'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'status'        => $this->input->post('status'),
                'hak_akses'     => $this->input->post('hak_akses') ? $this->input->post('hak_akses') : 2,
                'photo'         => $photo,
                'unit'          => $unit,
            );

            // Kolom SDM tambahan (hanya jika ada di tabel)
            $kolom_ada  = $this->_kolom_ada();
            $kolom_sdm  = array(
                'email'                    => $this->input->post('email'),
                'gelar'                    => $this->input->post('gelar'),
                'nama_panggilan'           => $this->input->post('nama_panggilan'),
                'tempat_lahir'             => $this->input->post('tempat_lahir'),
                'tanggal_lahir'            => $this->input->post('tanggal_lahir'),
                'golongan_darah'           => $this->input->post('golongan_darah'),
                'suku'                     => $this->input->post('suku'),
                'agama'                    => $this->input->post('agama'),
                'kewarganegaraan'          => $this->input->post('kewarganegaraan') ?: 'WNI',
                'no_kk'                    => $this->input->post('no_kk'),
                'npwp'                     => $this->input->post('npwp'),
                'alamat'                   => $this->input->post('alamat'),
                'desa'                     => $this->input->post('desa'),
                'kecamatan'                => $this->input->post('kecamatan'),
                'kabupaten'                => $this->input->post('kabupaten'),
                'anak_ke'                  => $this->input->post('anak_ke'),
                'dari_bersaudara'          => $this->input->post('dari_bersaudara'),
                'tinggal_bersama'          => $this->input->post('tinggal_bersama'),
                'jumlah_tanggungan'        => $this->input->post('jumlah_tanggungan') ?: 0,
                'rekening_atas_nama'       => $this->input->post('rekening_atas_nama'),
                'no_rekening'              => $this->input->post('no_rekening'),
                'karakter_pribadi'         => $this->input->post('karakter_pribadi'),
                'hal_tidak_disukai'        => $this->input->post('hal_tidak_disukai'),
                'ibadah_wajib_ketinggalan' => $this->input->post('ibadah_wajib_ketinggalan'),
                'ibadah_sunnah'            => $this->input->post('ibadah_sunnah'),
                'jumlah_buku'              => $this->input->post('jumlah_buku') ?: 0,
                'judul_buku_dibaca'        => $this->input->post('judul_buku_dibaca'),
                'akun_sosmed'              => $this->input->post('akun_sosmed'),
                'sosmed_sering'            => $this->input->post('sosmed_sering'),
                'konten_digemari'          => $this->input->post('konten_digemari'),
                'hobi'                     => $this->input->post('hobi'),
                'minat_bakat'              => $this->input->post('minat_bakat'),
                'keahlian'                 => $this->input->post('keahlian'),
                'tokoh_dikagumi'           => $this->input->post('tokoh_dikagumi'),
                'riwayat_penyakit'         => $this->input->post('riwayat_penyakit'),
                'status_penyakit'          => $this->input->post('status_penyakit'),
                'pantangan'                => $this->input->post('pantangan'),
            );

            foreach($kolom_sdm as $kolom => $nilai) {
                if(in_array($kolom, $kolom_ada)) {
                    $data[$kolom] = $nilai;
                }
            }

            $this->ModelPenggajian->insert_data($data, 'data_pegawai');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data pegawai berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE DATA
    // ─────────────────────────────────────────────────────────
    public function update_data($id)
    {
        $my_unit = $this->_my_unit();

        if($my_unit) {
            $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
            if($cek == 0) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Akses Ditolak!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button></div>');
                redirect('admin/data_pegawai');
            }
        }

        $data['title']     = "Update Data Pegawai";
        $data['jabatan']   = $this->ModelPenggajian->get_data('data_jabatan')->result();
        $data['pegawai']   = $this->db->query("SELECT * FROM data_pegawai WHERE id_pegawai = '$id'")->result();
        $data['my_unit']   = $my_unit;
        $data['hak_akses'] = $this->session->userdata('hak_akses');

        if($my_unit) {
            $unit_obj      = $this->ModelYayasan->get_unit_by_code($my_unit);
            $data['units'] = $unit_obj ? array($unit_obj) : array();
        } else {
            $data['units'] = $this->ModelYayasan->get_active_units();
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/update_dataPegawai', $data);
        $this->load->view('template_admin/footer');
    }

    public function update_data_aksi()
    {
        $this->_rules();

        if($this->form_validation->run() == FALSE) {
            $this->update_data($this->input->post('id_pegawai'));
        } else {
            $my_unit = $this->_my_unit();
            $id      = $this->input->post('id_pegawai');
            $unit    = $my_unit ? $my_unit : $this->input->post('unit');

            if($my_unit) {
                $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
                if($cek == 0) redirect('admin/data_pegawai');
            }

            $data_update = array(
                'nik'           => $this->input->post('nik'),
                'nama_pegawai'  => $this->input->post('nama_pegawai'),
                'username'      => $this->input->post('username'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan'       => $this->input->post('jabatan'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'status'        => $this->input->post('status'),
                'hak_akses'     => $this->input->post('hak_akses') ? $this->input->post('hak_akses') : 2,
                'unit'          => $unit,
            );

            // Password hanya update jika diisi
            $pass = $this->input->post('password');
            if(!empty($pass)) {
                $data_update['password'] = md5($pass);
            }

            // Photo hanya update jika ada file baru
            if(!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = './photo';
                $config['allowed_types'] = 'jpg|jpeg|png|tiff';
                $config['max_size']      = 2048;
                $config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()), 0, 10);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')) {
                    $data_update['photo'] = $this->upload->data('file_name');
                }
            }

            // Kolom SDM tambahan
            $kolom_ada = $this->_kolom_ada();
            $kolom_sdm = array(
                'email'                    => $this->input->post('email'),
                'gelar'                    => $this->input->post('gelar'),
                'nama_panggilan'           => $this->input->post('nama_panggilan'),
                'tempat_lahir'             => $this->input->post('tempat_lahir'),
                'tanggal_lahir'            => $this->input->post('tanggal_lahir'),
                'golongan_darah'           => $this->input->post('golongan_darah'),
                'suku'                     => $this->input->post('suku'),
                'agama'                    => $this->input->post('agama'),
                'kewarganegaraan'          => $this->input->post('kewarganegaraan') ?: 'WNI',
                'no_kk'                    => $this->input->post('no_kk'),
                'npwp'                     => $this->input->post('npwp'),
                'alamat'                   => $this->input->post('alamat'),
                'desa'                     => $this->input->post('desa'),
                'kecamatan'                => $this->input->post('kecamatan'),
                'kabupaten'                => $this->input->post('kabupaten'),
                'anak_ke'                  => $this->input->post('anak_ke'),
                'dari_bersaudara'          => $this->input->post('dari_bersaudara'),
                'tinggal_bersama'          => $this->input->post('tinggal_bersama'),
                'jumlah_tanggungan'        => $this->input->post('jumlah_tanggungan') ?: 0,
                'rekening_atas_nama'       => $this->input->post('rekening_atas_nama'),
                'no_rekening'              => $this->input->post('no_rekening'),
                'karakter_pribadi'         => $this->input->post('karakter_pribadi'),
                'hal_tidak_disukai'        => $this->input->post('hal_tidak_disukai'),
                'ibadah_wajib_ketinggalan' => $this->input->post('ibadah_wajib_ketinggalan'),
                'ibadah_sunnah'            => $this->input->post('ibadah_sunnah'),
                'jumlah_buku'              => $this->input->post('jumlah_buku') ?: 0,
                'judul_buku_dibaca'        => $this->input->post('judul_buku_dibaca'),
                'akun_sosmed'              => $this->input->post('akun_sosmed'),
                'sosmed_sering'            => $this->input->post('sosmed_sering'),
                'konten_digemari'          => $this->input->post('konten_digemari'),
                'hobi'                     => $this->input->post('hobi'),
                'minat_bakat'              => $this->input->post('minat_bakat'),
                'keahlian'                 => $this->input->post('keahlian'),
                'tokoh_dikagumi'           => $this->input->post('tokoh_dikagumi'),
                'riwayat_penyakit'         => $this->input->post('riwayat_penyakit'),
                'status_penyakit'          => $this->input->post('status_penyakit'),
                'pantangan'                => $this->input->post('pantangan'),
            );

            foreach($kolom_sdm as $kolom => $nilai) {
                if(in_array($kolom, $kolom_ada)) {
                    $data_update[$kolom] = $nilai;
                }
            }

            $this->ModelPenggajian->update_data('data_pegawai', $data_update, array('id_pegawai' => $id));
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil diupdate!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    // ─────────────────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────────────────
    public function delete_data($id)
    {
        $my_unit = $this->_my_unit();

        if($my_unit) {
            $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
            if($cek == 0) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Akses Ditolak!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button></div>');
                redirect('admin/data_pegawai');
            }
        }

        $this->ModelPenggajian->delete_data(array('id_pegawai' => $id), 'data_pegawai');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data berhasil dihapus!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/data_pegawai');
    }

    // ─────────────────────────────────────────────────────────
    // Validation rules
    // ─────────────────────────────────────────────────────────
    public function _rules()
    {
        $this->form_validation->set_rules('nik',           'NIK',           'required');
        $this->form_validation->set_rules('nama_pegawai',  'Nama Pegawai',  'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('jabatan',       'Jabatan',       'required');
        $this->form_validation->set_rules('status',        'Status',        'required');
    }
}