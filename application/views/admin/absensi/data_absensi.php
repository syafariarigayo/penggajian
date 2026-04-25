<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Absensi extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Laporan Absensi Pegawai";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/laporan_absensi');
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_absensi()
    {
        $data['title'] = "Cetak Laporan Absensi Pegawai";
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

        $data['lap_kehadiran'] = $this->db->query(
            "SELECT dk.*
             FROM data_kehadiran dk
             INNER JOIN data_pegawai dp ON dk.nik = dp.nik
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dk.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/absensi/cetak_absensi', $data);
    }
}