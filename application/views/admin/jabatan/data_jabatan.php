<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Absensi extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Absensi Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['absensi'] = $this->db->query(
            "SELECT dk.*, dp.nama_pegawai, dp.jenis_kelamin, dp.jabatan, dj.nama_jabatan
             FROM data_kehadiran dk
             INNER JOIN data_pegawai dp ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/data_absensi', $data);
        $this->load->view('template_admin/footer');
    }

    public function input_absensi()
    {
        if($this->input->post('submit', TRUE) == 'submit') {
            $post   = $this->input->post();
            $simpan = array();
            foreach ($post['bulan'] as $key => $value) {
                if($post['bulan'][$key] != '' || $post['nik'][$key] != '') {
                    $simpan[] = array(
                        'bulan'         => $post['bulan'][$key],
                        'nik'           => $post['nik'][$key],
                        'nama_pegawai'  => $post['nama_pegawai'][$key],
                        'jenis_kelamin' => $post['jenis_kelamin'][$key],
                        'nama_jabatan'  => $post['nama_jabatan'][$key],
                        'hadir'         => $post['hadir'][$key],
                        'sakit'         => $post['sakit'][$key],
                        'alpha'         => $post['alpha'][$key],
                    );
                }
            }
            $this->ModelPenggajian->insert_batch('data_kehadiran', $simpan);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_absensi');
        }

        $data['title'] = "Form Input Absensi";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['input_absensi'] = $this->db->query(
            "SELECT dp.*, dj.nama_jabatan
             FROM data_pegawai dp
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE NOT EXISTS (
                 SELECT * FROM data_kehadiran
                 WHERE bulan = '$bulantahun' AND dp.nik = data_kehadiran.nik
             ) $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/tambah_dataAbsensi', $data);
        $this->load->view('template_admin/footer');
    }
}