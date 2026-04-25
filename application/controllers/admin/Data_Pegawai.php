<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Pegawai extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $hak = $this->session->userdata('hak_akses');
        if($hak != '1' && $hak != '3'){
            redirect('login');
        }
    }

    public function index()
    {
        $data['title']     = "Data Pegawai";
        $data['my_unit']   = null;
        $data['nama_unit'] = 'Semua Unit';
        $data['hak_akses'] = $this->session->userdata('hak_akses');
        $data['pegawai']   = $this->db->get('data_pegawai')->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/data_pegawai', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_data()
    {
        $data['title']     = "Tambah Data Pegawai";
        $data['jabatan']   = $this->db->get('data_jabatan')->result();
        $data['my_unit']   = null;
        $data['hak_akses'] = $this->session->userdata('hak_akses');
        $data['units']     = $this->db->where('status','Aktif')->get('data_unit')->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/tambah_dataPegawai', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_data_aksi()
    {
        $this->form_validation->set_rules('nik',          'NIK',          'required');
        $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
        $this->form_validation->set_rules('jenis_kelamin','Jenis Kelamin','required');
        $this->form_validation->set_rules('tanggal_masuk','Tanggal Masuk','required');
        $this->form_validation->set_rules('jabatan',      'Jabatan',      'required');
        $this->form_validation->set_rules('status',       'Status',       'required');

        if($this->form_validation->run() == FALSE){
            $this->tambah_data();
        } else {
            $photo = '';
            if(!empty($_FILES['photo']['name'])){
                $config['upload_path']   = './photo';
                $config['allowed_types'] = 'jpg|jpeg|png|tiff';
                $config['max_size']      = 2048;
                $config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()),0,10);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')){
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
                'hak_akses'     => $this->input->post('hak_akses') ?: 2,
                'photo'         => $photo,
                'unit'          => $this->input->post('unit'),
            );

            $this->db->insert('data_pegawai', $data);
            $this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    public function update_data($id)
    {
        $data['title']     = "Update Data Pegawai";
        $data['jabatan']   = $this->db->get('data_jabatan')->result();
        $data['pegawai']   = $this->db->get_where('data_pegawai', array('id_pegawai' => $id))->result();
        $data['my_unit']   = null;
        $data['hak_akses'] = $this->session->userdata('hak_akses');
        $data['units']     = $this->db->where('status','Aktif')->get('data_unit')->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/pegawai/update_dataPegawai', $data);
        $this->load->view('template_admin/footer');
    }

    public function update_data_aksi()
    {
        $this->form_validation->set_rules('nik',          'NIK',          'required');
        $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
        $this->form_validation->set_rules('jenis_kelamin','Jenis Kelamin','required');
        $this->form_validation->set_rules('tanggal_masuk','Tanggal Masuk','required');
        $this->form_validation->set_rules('jabatan',      'Jabatan',      'required');
        $this->form_validation->set_rules('status',       'Status',       'required');

        if($this->form_validation->run() == FALSE){
            $this->update_data($this->input->post('id_pegawai'));
        } else {
            $id = $this->input->post('id_pegawai');

            $data = array(
                'nik'           => $this->input->post('nik'),
                'nama_pegawai'  => $this->input->post('nama_pegawai'),
                'username'      => $this->input->post('username'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan'       => $this->input->post('jabatan'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'status'        => $this->input->post('status'),
                'hak_akses'     => $this->input->post('hak_akses') ?: 2,
                'unit'          => $this->input->post('unit'),
            );

            $pass = $this->input->post('password');
            if(!empty($pass)){
                $data['password'] = md5($pass);
            }

            if(!empty($_FILES['photo']['name'])){
                $config['upload_path']   = './photo';
                $config['allowed_types'] = 'jpg|jpeg|png|tiff';
                $config['max_size']      = 2048;
                $config['file_name']     = 'pegawai-'.date('ymd').'-'.substr(md5(rand()),0,10);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')){
                    $data['photo'] = $this->upload->data('file_name');
                }
            }

            $this->db->where('id_pegawai', $id);
            $this->db->update('data_pegawai', $data);
            $this->session->set_flashdata('pesan','<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil diupdate!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_pegawai');
        }
    }

    public function delete_data($id)
    {
        $this->db->where('id_pegawai', $id);
        $this->db->delete('data_pegawai');
        $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Data berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/data_pegawai');
    }
}