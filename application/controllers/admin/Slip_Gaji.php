<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slip_Gaji extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Slip Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if($my_unit) {
            $data['pegawai'] = $this->db->where('unit', $my_unit)->get('data_pegawai')->result();
        } else {
            $data['pegawai'] = $this->ModelPenggajian->get_data('data_pegawai')->result();
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/slip_gaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_slip_gaji()
    {
        $data['title']    = "Cetak Slip Gaji Pegawai";
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $nama             = $this->input->post('nama_pegawai');
        $bulan            = $this->input->post('bulan');
        $tahun            = $this->input->post('tahun');
        $bulantahun       = $bulan . $tahun;

        $data['print_slip'] = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dj.nama_jabatan,
                    dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha, dk.bulan
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '" . $this->db->escape_str($bulantahun) . "'
               AND dp.nama_pegawai = '" . $this->db->escape_str($nama) . "'"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/gaji/cetak_slip_gaji', $data);
    }
}
