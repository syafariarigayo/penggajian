<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Absensi Fingerprint
 * Letakkan di: application/controllers/admin/Absensi_Fingerprint.php
 */
class Absensi_Fingerprint extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ModelFingerprint');
        $this->cek_admin();
    }

    // =====================================================
    // Dashboard Absensi Hari Ini
    // =====================================================
    public function index()
    {
        $data['title']   = "Absensi Fingerprint";
        $my_unit         = $this->get_my_unit();
        $data['my_unit'] = $my_unit;
        $data['today']   = date('Y-m-d');

        // Rekap hari ini
        $data['rekap_hari_ini'] = $this->ModelFingerprint->get_rekap_harian(
            date('Y-m-d'), $my_unit
        );

        // Statistik hari ini
        $data['stats'] = $this->ModelFingerprint->get_statistik_hari_ini($my_unit);

        // Daftar device
        $data['devices'] = $this->ModelFingerprint->get_devices();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/fingerprint/dashboard_fingerprint', $data);
        $this->load->view('template_admin/footer');
    }

    // =====================================================
    // Rekap Bulanan
    // =====================================================
    public function rekap_bulanan()
    {
        $data['title'] = "Rekap Absensi Fingerprint";
        $my_unit       = $this->get_my_unit();

        $bulan = $this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $bulantahun = $bulan . $tahun;

        $data['bulan']      = $bulan;
        $data['tahun']      = $tahun;
        $data['bulantahun'] = $bulantahun;
        $data['my_unit']    = $my_unit;

        $data['rekap'] = $this->ModelFingerprint->get_rekap_bulanan($bulantahun, $my_unit);

        // Hitung statistik bulanan
        $data['total_hadir']     = 0;
        $data['total_terlambat'] = 0;
        $data['total_alpha']     = 0;

        foreach($data['rekap'] as $r) {
            if($r->status_kehadiran == 'Hadir')     $data['total_hadir']++;
            if($r->status_kehadiran == 'Terlambat') $data['total_terlambat']++;
            if($r->status_kehadiran == 'Alpha')     $data['total_alpha']++;
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/fingerprint/rekap_bulanan', $data);
        $this->load->view('template_admin/footer');
    }

    // =====================================================
    // Detail log fingerprint per pegawai per hari
    // =====================================================
    public function detail_log($nik, $tanggal)
    {
        $data['title']   = "Detail Log Fingerprint";
        $data['pegawai'] = $this->db->get_where('data_pegawai', ['nik' => $nik])->row();
        $data['tanggal'] = $tanggal;
        $data['logs']    = $this->ModelFingerprint->get_attendance_log($nik, $tanggal);
        $data['rekap']   = $this->db->get_where('rekap_absensi', [
            'nik'     => $nik,
            'tanggal' => $tanggal
        ])->row();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/fingerprint/detail_log', $data);
        $this->load->view('template_admin/footer');
    }

    // =====================================================
    // Kelola Device/Mesin Fingerprint
    // =====================================================
    public function kelola_device()
    {
        $this->cek_super_admin(); // Hanya super admin

        $data['title']   = "Kelola Mesin Fingerprint";
        $data['devices'] = $this->ModelFingerprint->get_devices();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/fingerprint/kelola_device', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_device_aksi()
    {
        $this->cek_super_admin();

        $data = [
            'device_id'   => $this->input->post('device_id'),
            'device_name' => $this->input->post('device_name'),
            'unit'        => $this->input->post('unit'),
            'ip_address'  => $this->input->post('ip_address'),
            'lokasi'      => $this->input->post('lokasi'),
            'status'      => 'aktif',
        ];

        $this->db->insert('fingerprint_device', $data);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Device berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/absensi_fingerprint/kelola_device');
    }

    public function delete_device($id)
    {
        $this->cek_super_admin();
        $this->db->where('id', $id)->delete('fingerprint_device');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Device berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/absensi_fingerprint/kelola_device');
    }

    // =====================================================
    // Setting Jam Kerja
    // =====================================================
    public function setting_jam_kerja()
    {
        $data['title']    = "Setting Jam Kerja";
        $data['settings'] = $this->db->get('setting_jam_kerja')->result();
        $data['units']    = $this->ModelYayasan->get_active_units();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/fingerprint/setting_jam_kerja', $data);
        $this->load->view('template_admin/footer');
    }

    public function update_jam_kerja_aksi()
    {
        $id  = $this->input->post('id');
        $data = [
            'jam_masuk'       => $this->input->post('jam_masuk'),
            'jam_pulang'      => $this->input->post('jam_pulang'),
            'toleransi_menit' => $this->input->post('toleransi_menit'),
            'is_libur'        => $this->input->post('is_libur') ? 1 : 0,
        ];

        $this->db->where('id', $id)->update('setting_jam_kerja', $data);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Setting berhasil disimpan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/absensi_fingerprint/setting_jam_kerja');
    }

    // =====================================================
    // Proses ulang rekap (jika ada perubahan setting)
    // =====================================================
    public function proses_ulang($bulan, $tahun)
    {
        $this->cek_super_admin();
        $bulantahun = $bulan . $tahun;

        // Ambil semua attendance_log bulan ini
        $logs = $this->db->query(
            "SELECT DISTINCT nik, DATE(waktu) as tanggal 
             FROM attendance_log 
             WHERE DATE_FORMAT(waktu, '%m%Y') = '" . $this->db->escape_str($bulantahun) . "'"
        )->result();

        $proses = 0;
        foreach($logs as $log) {
            $this->ModelFingerprint->proses_rekap_harian($log->nik, $log->tanggal);
            $proses++;
        }

        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'.$proses.' record berhasil diproses ulang!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/absensi_fingerprint/rekap_bulanan?bulan='.$bulan.'&tahun='.$tahun);
    }
}
