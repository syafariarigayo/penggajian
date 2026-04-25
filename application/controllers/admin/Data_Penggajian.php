<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Penggajian extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '" . $this->db->escape_str($my_unit) . "'" : "";

        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['gaji']     = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dp.jenis_kelamin,
                    dj.nama_jabatan, dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '" . $this->db->escape_str($bulantahun) . "' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/data_gaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_gaji()
    {
        $data['title'] = "Cetak Data Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '" . $this->db->escape_str($my_unit) . "'" : "";

        $data['potongan']   = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dp.jenis_kelamin,
                    dj.nama_jabatan, dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '" . $this->db->escape_str($bulantahun) . "' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/gaji/cetak_gaji', $data);
    }
}
