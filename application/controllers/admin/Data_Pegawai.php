<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Pegawai extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title']     = "Data Pegawai";
        $my_unit           = $this->get_my_unit();
        $data['my_unit']   = $my_unit;
        $data['hak_akses'] = $this->session->userdata('hak_akses');

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

    public function tambah_data()
    {
        $data['title']     = "Tambah Data Pegawai";
        $data['jabatan']   = $this->ModelPenggajian->get_data('data_jabatan')->result();
        $my_unit           = $this->get_my_unit();
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
            $my_unit = $this->get_my_unit();
            $unit    = $my_unit ? $my_unit : $this->input->post('unit');

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
                'tempat_lahir'  => $this->input->post('tempat_lahir'),
            );

            $this->ModelPenggajian->insert_data($data, 'data_pegawai');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    public function update_data($id)
    {
        $my_unit = $this->get_my_unit();

        // Admin unit hanya bisa edit pegawai di unitnya sendiri
        if($my_unit) {
            $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
            if($cek == 0) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Akses Ditolak!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                redirect('admin/data_pegawai');
            }
        }

        $data['title']     = "Update Data Pegawai";
        $data['jabatan']   = $this->ModelPenggajian->get_data('data_jabatan')->result();
        $data['pegawai']   = $this->db->get_where('data_pegawai', array('id_pegawai' => $id))->result();
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
            $my_unit = $this->get_my_unit();
            $id      = $this->input->post('id_pegawai');
            $unit    = $my_unit ? $my_unit : $this->input->post('unit');

            if($my_unit) {
                $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
                if($cek == 0) redirect('admin/data_pegawai');
            }

            $data = array(
                'nik'           => $this->input->post('nik'),
                'nama_pegawai'  => $this->input->post('nama_pegawai'),
                'username'      => $this->input->post('username'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan'       => $this->input->post('jabatan'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'status'        => $this->input->post('status'),
                'hak_akses'     => $this->input->post('hak_akses') ? $this->input->post('hak_akses') : 2,
                'unit'          => $unit,
                'tempat_lahir'  => $this->input->post('tempat_lahir'),
            );

            // Hanya update password jika diisi
            $pass = $this->input->post('password');
            if(!empty($pass)) {
                $data['password'] = md5($pass);
            }

            // Upload foto baru jika ada
            if(!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = './photo';
                $config['allowed_types'] = 'jpg|jpeg|png|tiff';
                $config['max_size']      = 2048;
                $config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()), 0, 10);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')) {
                    $data['photo'] = $this->upload->data('file_name');
                }
            }

            $this->ModelPenggajian->update_data('data_pegawai', $data, array('id_pegawai' => $id));
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil diupdate!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    public function delete_data($id)
    {
        $my_unit = $this->get_my_unit();
        if($my_unit) {
            $cek = $this->db->get_where('data_pegawai', array('id_pegawai' => $id, 'unit' => $my_unit))->num_rows();
            if($cek == 0) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Akses Ditolak!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                redirect('admin/data_pegawai');
            }
        }

        $this->ModelPenggajian->delete_data(array('id_pegawai' => $id), 'data_pegawai');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Data berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/data_pegawai');
    }

    private function _rules()
    {
        $this->form_validation->set_rules('nik',          'NIK',          'required');
        $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
        $this->form_validation->set_rules('jenis_kelamin','Jenis Kelamin','required');
        $this->form_validation->set_rules('tanggal_masuk','Tanggal Masuk','required');
        $this->form_validation->set_rules('jabatan',      'Jabatan',      'required');
        $this->form_validation->set_rules('status',       'Status',       'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
    }
}
