<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_Karyawan extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Penilaian Karyawan";
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

        $data['penilaian'] = $this->db->query(
            "SELECT dpn.*
             FROM data_penilaian dpn
             INNER JOIN data_pegawai dp ON dpn.nik = dp.nik
             WHERE dpn.bulan = '$bulantahun' $unit_filter
             ORDER BY dpn.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/data_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function input_penilaian()
    {
        if($this->input->post('submit', TRUE) == 'submit') {
            $post   = $this->input->post();
            $simpan = array();
            foreach ($post['bulan'] as $key => $value) {
                if($post['bulan'][$key] != '' || $post['nik'][$key] != '') {
                    $total = $post['kedisiplinan'][$key] + $post['kerjasama'][$key]
                           + $post['tanggung_jawab'][$key] + $post['kualitas_kerja'][$key];
                    if($total >= 340)     $kategori = "Sangat Baik";
                    elseif($total >= 260) $kategori = "Baik";
                    elseif($total >= 180) $kategori = "Cukup";
                    else                  $kategori = "Kurang";

                    $simpan[] = array(
                        'bulan'          => $post['bulan'][$key],
                        'nik'            => $post['nik'][$key],
                        'nama_pegawai'   => $post['nama_pegawai'][$key],
                        'jabatan'        => $post['jabatan'][$key],
                        'kedisiplinan'   => $post['kedisiplinan'][$key],
                        'kerjasama'      => $post['kerjasama'][$key],
                        'tanggung_jawab' => $post['tanggung_jawab'][$key],
                        'kualitas_kerja' => $post['kualitas_kerja'][$key],
                        'total_nilai'    => $total,
                        'kategori'       => $kategori,
                    );
                }
            }
            $this->ModelPenggajian->insert_batch('data_penilaian', $simpan);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/penilaian_karyawan');
        }

        $data['title'] = "Form Input Penilaian Karyawan";
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

        $data['input_penilaian'] = $this->db->query(
            "SELECT dp.*, dj.nama_jabatan
             FROM data_pegawai dp
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE NOT EXISTS (
                 SELECT * FROM data_penilaian
                 WHERE bulan = '$bulantahun' AND dp.nik = data_penilaian.nik
             ) $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/tambah_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function edit_penilaian($id)
    {
        $data['title']     = "Edit Penilaian Karyawan";
        $data['penilaian'] = $this->db->query("SELECT * FROM data_penilaian WHERE id_penilaian = '$id'")->result();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/edit_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function edit_penilaian_aksi()
    {
        $id             = $this->input->post('id_penilaian');
        $kedisiplinan   = $this->input->post('kedisiplinan');
        $kerjasama      = $this->input->post('kerjasama');
        $tanggung_jawab = $this->input->post('tanggung_jawab');
        $kualitas_kerja = $this->input->post('kualitas_kerja');
        $total          = $kedisiplinan + $kerjasama + $tanggung_jawab + $kualitas_kerja;

        if($total >= 340)     $kategori = "Sangat Baik";
        elseif($total >= 260) $kategori = "Baik";
        elseif($total >= 180) $kategori = "Cukup";
        else                  $kategori = "Kurang";

        $this->ModelPenggajian->update_data('data_penilaian', array(
            'kedisiplinan'   => $kedisiplinan,
            'kerjasama'      => $kerjasama,
            'tanggung_jawab' => $tanggung_jawab,
            'kualitas_kerja' => $kualitas_kerja,
            'total_nilai'    => $total,
            'kategori'       => $kategori,
        ), array('id_penilaian' => $id));

        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil diupdate!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/penilaian_karyawan');
    }

    public function delete_penilaian($id)
    {
        $this->ModelPenggajian->delete_data(array('id_penilaian' => $id), 'data_penilaian');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/penilaian_karyawan');
    }

    public function laporan_penilaian()
    {
        $data['title'] = "Laporan Penilaian Karyawan";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/laporan_penilaian');
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_penilaian()
    {
        $data['title'] = "Cetak Laporan Penilaian Karyawan";
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

        $data['lap_penilaian'] = $this->db->query(
            "SELECT dpn.*
             FROM data_penilaian dpn
             INNER JOIN data_pegawai dp ON dpn.nik = dp.nik
             WHERE dpn.bulan = '$bulantahun' $unit_filter
             ORDER BY dpn.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/penilaian/cetak_penilaian', $data);
    }
}