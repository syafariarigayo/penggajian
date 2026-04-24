<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin(); // izinkan hak_akses 1 & 3
    }

    public function index()
    {
        $data['title']     = "Dashboard";
        $id_pegawai        = $this->session->userdata('id_pegawai');
        $hak_akses         = $this->session->userdata('hak_akses');
        $my_unit           = $this->get_my_unit();

        $pegawai = $this->db->get_where('data_pegawai', array('id_pegawai' => $id_pegawai))->row();

        // Tentukan unit yang akan ditampilkan
        if($hak_akses == 1) {
            $selected_unit = $this->input->get('unit') ? $this->input->get('unit') : 'ALL';
        } else {
            // Kepala unit hanya lihat unitnya sendiri
            $selected_unit = $my_unit;
        }

        $data['selected_unit'] = $selected_unit;
        $data['user_unit']     = $pegawai ? $pegawai->unit : '';
        $data['hak_akses']     = $hak_akses;

        if($hak_akses == 1) {
            $data['units'] = $this->ModelYayasan->get_active_units();
        } else {
            $unit_obj      = $this->ModelYayasan->get_unit_by_code($my_unit);
            $data['units'] = $unit_obj ? array($unit_obj) : array();
        }

        // ── Statistik ─────────────────────────────────────────────────────────
        if($selected_unit == 'ALL') {
            $data['total_unit'] = $this->db->where('kode_unit !=', 'YYS')
                                    ->where('status', 'Aktif')
                                    ->count_all_results('data_unit');
            $data['total_pegawai']       = $this->db->count_all('data_pegawai');
            $data['pegawai_tetap']       = $this->db->where('status','Karyawan Tetap')->count_all_results('data_pegawai');
            $data['pegawai_tidak_tetap'] = $this->db->where('status','Karyawan Tidak Tetap')->count_all_results('data_pegawai');

            $query = "SELECT j.kategori, COUNT(*) as jumlah
                      FROM data_pegawai p
                      INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
                      GROUP BY j.kategori";
            $data['per_kategori'] = $this->db->query($query)->result();
        } else {
            $data['total_unit']          = 1;
            $data['total_pegawai']       = $this->db->where('unit',$selected_unit)->count_all_results('data_pegawai');
            $data['pegawai_tetap']       = $this->db->where('unit',$selected_unit)->where('status','Karyawan Tetap')->count_all_results('data_pegawai');
            $data['pegawai_tidak_tetap'] = $this->db->where('unit',$selected_unit)->where('status','Karyawan Tidak Tetap')->count_all_results('data_pegawai');

            $query = "SELECT j.kategori, COUNT(*) as jumlah
                      FROM data_pegawai p
                      INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
                      WHERE p.unit = '$selected_unit'
                      GROUP BY j.kategori";
            $data['per_kategori'] = $this->db->query($query)->result();
        }

        // ── Jenis kelamin ─────────────────────────────────────────────────────
        if($selected_unit == 'ALL') {
            $data['jk_laki']     = $this->db->where('jenis_kelamin','Laki-Laki')->count_all_results('data_pegawai');
            $data['jk_perempuan']= $this->db->where('jenis_kelamin','Perempuan')->count_all_results('data_pegawai');
        } else {
            $data['jk_laki']     = $this->db->where('unit',$selected_unit)->where('jenis_kelamin','Laki-Laki')->count_all_results('data_pegawai');
            $data['jk_perempuan']= $this->db->where('unit',$selected_unit)->where('jenis_kelamin','Perempuan')->count_all_results('data_pegawai');
        }

        // ── Status kepegawaian ────────────────────────────────────────────────
        if($selected_unit == 'ALL') {
            $query_status = "SELECT status_kepegawaian, COUNT(*) as jumlah FROM data_pegawai GROUP BY status_kepegawaian";
        } else {
            $query_status = "SELECT status_kepegawaian, COUNT(*) as jumlah FROM data_pegawai WHERE unit='$selected_unit' GROUP BY status_kepegawaian";
        }
        $data['per_status'] = $this->db->query($query_status)->result();

        // ── Absensi bulan ini ─────────────────────────────────────────────────
        $bulan_ini = date('m').date('Y');
        if($selected_unit == 'ALL') {
            $data['absensi_bulan_ini'] = $this->db->where('bulan',$bulan_ini)->count_all_results('data_kehadiran');
        } else {
            $data['absensi_bulan_ini'] = $this->db->where('unit',$selected_unit)->where('bulan',$bulan_ini)->count_all_results('data_kehadiran');
        }

        // ── Top 5 unit (Super Admin ALL) ──────────────────────────────────────
        if($hak_akses == 1 && $selected_unit == 'ALL') {
            $data['top_units'] = $this->db->query(
                "SELECT u.nama_unit, u.kode_unit, COUNT(p.id_pegawai) as jumlah
                 FROM data_unit u
                 LEFT JOIN data_pegawai p ON u.kode_unit = p.unit
                 WHERE u.kode_unit != 'YYS' AND u.status = 'Aktif'
                 GROUP BY u.kode_unit, u.nama_unit
                 ORDER BY jumlah DESC LIMIT 5"
            )->result();
        } else {
            $data['top_units'] = array();
        }

        // ── Nama unit display ─────────────────────────────────────────────────
        if($selected_unit != 'ALL') {
            $unit_info       = $this->ModelYayasan->get_unit_by_code($selected_unit);
            $data['nama_unit'] = $unit_info ? $unit_info->nama_unit : $selected_unit;
        } else {
            $data['nama_unit'] = 'Semua Unit Yayasan';
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/dashboard_multiunit', $data);
        $this->load->view('template_admin/footer');
    }

    // AJAX chart data
    public function get_chart_data()
    {
        $unit       = $this->input->get('unit');
        $chart_type = $this->input->get('type');
        $response   = array();

        switch($chart_type) {
            case 'kategori':
                $where = ($unit == 'ALL') ? '' : "WHERE p.unit = '$unit'";
                $query = "SELECT j.kategori, COUNT(*) as jumlah
                          FROM data_pegawai p
                          INNER JOIN data_jabatan j ON p.jabatan = j.nama_jabatan
                          $where GROUP BY j.kategori";
                $response = $this->db->query($query)->result();
                break;

            case 'jenis_kelamin':
                if($unit == 'ALL') {
                    $laki     = $this->db->where('jenis_kelamin','Laki-Laki')->count_all_results('data_pegawai');
                    $perempuan= $this->db->where('jenis_kelamin','Perempuan')->count_all_results('data_pegawai');
                } else {
                    $laki     = $this->db->where('unit',$unit)->where('jenis_kelamin','Laki-Laki')->count_all_results('data_pegawai');
                    $perempuan= $this->db->where('unit',$unit)->where('jenis_kelamin','Perempuan')->count_all_results('data_pegawai');
                }
                $response = array('laki' => $laki, 'perempuan' => $perempuan);
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}